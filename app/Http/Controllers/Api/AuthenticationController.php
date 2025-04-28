<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewUserRegistered;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpVerificationMail;
use Carbon\Carbon;

class AuthenticationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['apiRegister', 'apiLogin', 'verifyOtp', 'requestOtpResetPassword', 'resetPasswordWithOtp']);
    }

    public function apiRegister(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required|string|min:6|same:password',
            ]);

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user'
            ]);

            $otp = rand(100000, 999999); // 6 digit OTP
            $expiresAt = now()->addMinutes(5); // OTP berlaku 5 menit

            $user->otp_code = $otp;
            $user->otp_expires_at = $expiresAt;
            $user->save();

            // Bisa kirim OTP via email
            Mail::to($user->email)->send(new \App\Mail\OtpVerificationMail($user));

            // Kirim notifikasi ke semua admin
            $admins = User::where('role', 'admin')->get();
            Notification::send($admins, new NewUserRegistered($user));

            return response()->json([
                'message' => 'Registrasi berhasil. Silakan login.',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function apiLogin(Request $request)
    {
        try {
            // Validasi kredensial
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                /** @var \App\Models\User $user */
                $user = Auth::user();

                // Mengecek apakah akun sudah diverifikasi
                if (!$user->email_verified_at) {
                    // Jika belum diverifikasi, kirimkan OTP ke email pengguna
                    $otp = rand(100000, 999999); // 6 digit OTP
                    $expiresAt = now()->addMinutes(5); // OTP berlaku 5 menit

                    $user->otp_code = $otp;
                    $user->otp_expires_at = $expiresAt;
                    $user->save();

                    // Kirim OTP ke email pengguna
                    Mail::to($user->email)->send(new \App\Mail\OtpVerificationMail($user));

                    // Kirimkan respons bahwa OTP sudah dikirim
                    return response()->json([
                        'message' => 'Akun belum diverifikasi. OTP telah dikirimkan ke email Anda. Silakan verifikasi untuk melanjutkan login.',
                        'user' => $user
                    ], 200);
                }

                // Jika akun sudah diverifikasi, lanjutkan login dan berikan token
                if ($user->role === 'admin') {
                    Auth::logout();
                    return response()->json(['message' => 'Akun ini tidak diizinkan untuk login.'], 403);
                }

                // Generate token jika akun sudah diverifikasi
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'message' => 'Login berhasil sebagai user.',
                    'user' => $user,
                    'token' => $token,
                    'token_type' => 'Bearer'
                ], 200);
            }

            // Jika kredensial tidak valid
            return response()->json(['message' => 'Email atau password salah!'], 401);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function apiLogout(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['message' => 'User tidak terautentikasi.'], 401);
            }

            if ($user->currentAccessToken()) {
                $user->currentAccessToken()->delete();
                return response()->json(['message' => 'Logout berhasil.'], 200);
            }

            return response()->json(['message' => 'Token tidak ditemukan atau sudah dihapus.'], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function apiUpdatedata(Request $request)
    {
        try {
            // Memastikan user terautentikasi dengan token yang valid
            $user = $request->user();

            if (!$user) {
                return response()->json(['message' => 'Token tidak ditemukan atau sudah kedaluwarsa. Silakan login kembali.'], 401);
            }

            // Pastikan token valid (misalnya token telah kedaluwarsa)
            if (!Auth::check()) {
                return response()->json(['message' => 'Token tidak valid atau telah kedaluwarsa. Silakan login kembali.'], 401);
            }

            // Memeriksa apakah OTP perlu diverifikasi ulang
            $otpInput = $request->input('otp');

            if (!$otpInput) {
                // Jika OTP belum dimasukkan, kirimkan OTP dan beri tahu pengguna untuk memverifikasi
                $otp = rand(100000, 999999); // 6 digit OTP
                $expiresAt = now()->addMinutes(5); // OTP berlaku 5 menit

                $user->otp_code = $otp;
                $user->otp_expires_at = $expiresAt;
                $user->save();

                // Kirim OTP lewat email
                Mail::to($user->email)->send(new \App\Mail\OtpVerificationMail($user));

                // Kirim pesan untuk memverifikasi OTP
                return response()->json([
                    'message' => 'Harap verifikasi OTP yang telah dikirimkan ke email Anda sebelum memperbarui data.',
                    'otp_sent' => true,
                    'user' => $user
                ], 400);
            }

            // Jika OTP dimasukkan, periksa apakah valid
            if ($user->otp_code !== $otpInput || $user->otp_expires_at < now()) {
                return response()->json(['message' => 'Kode OTP tidak valid atau telah kedaluwarsa.'], 400);
            }

            // Setelah OTP diverifikasi, lanjutkan untuk update profil pengguna
            // Lakukan validasi dan update data user setelah verifikasi OTP
            $request->validate([
                'username' => 'required|string|max:50|unique:users,username,' . $user->id,
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:6|confirmed',
                'password_confirmation' => 'nullable|string|min:6|same:password',
            ]);

            // Update data user
            $user->username = $request->username;
            $user->email = $request->email;

            if ($request->password) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            // Reset OTP dan waktu kadaluarsa setelah profil diperbarui
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->save();

            return response()->json(['message' => 'Profil berhasil diperbarui.', 'user' => $user], 200);

        } catch (AuthenticationException $e) {
            return response()->json(['message' => 'Token tidak valid atau telah kedaluwarsa. Silakan login kembali.'], 401);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Data yang dimasukkan tidak valid.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        if ($user->otp_code !== $request->otp_code) {
            return response()->json(['message' => 'OTP tidak cocok'], 400);
        }

        if (now()->gt($user->otp_expires_at)) {
            return response()->json(['message' => 'OTP sudah kedaluwarsa'], 400);
        }

        $user->email_verified_at = now();
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        return response()->json(['message' => 'Email berhasil diverifikasi!']);
    }

    public function requestOtpResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();

        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(5);

        $user->otp_code = $otp;
        $user->otp_expires_at = $expiresAt;
        $user->save();

        Mail::to($user->email)->send(new OtpVerificationMail($user));

        return response()->json([
            'message' => 'Kode verifikasi telah dikirim ke email Anda.',
            'otp_sent' => true
        ]);
    }

    public function resetPasswordWithOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp_code' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6|same:password'
        ]);

        $user = User::where('email', $request->email)->first();

        if ((string) $user->otp_code !== (string) $request->otp_code || Carbon::parse($user->otp_expires_at)->isPast()) {
            return response()->json([
                'message' => 'Kode OTP salah atau telah kedaluwarsa.',
                'otp_valid' => false
            ], 400);
        }

        $user->password = Hash::make($request->password);
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        return response()->json([
            'message' => 'Password berhasil diperbarui.'
        ]);
    }
}
