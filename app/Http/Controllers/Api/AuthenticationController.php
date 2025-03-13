<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;

class AuthenticationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['apiRegister', 'apiLogin']);
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
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                if ($user->role === 'admin') {
                    Auth::logout();
                    return response()->json(['message' => 'Akun ini tidak diizinkan untuk login.'], 403);
                }

                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'message' => 'Login berhasil sebagai user.',
                    'user' => $user,
                    'token' => $token,
                    'token_type' => 'Bearer'
                ], 200);
            }

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

        // Lakukan validasi dan update data user
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'password_confirmation' => 'nullable|string|min:6|same:password',
        ]);

        $user->username = $request->username;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

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

}
