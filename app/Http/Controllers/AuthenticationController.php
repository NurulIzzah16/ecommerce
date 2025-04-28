<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username'              => 'required|unique:users',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6|same:password'
        ]);

        // Simpan ke variabel $user
        $user = User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin'
        ]);

        // Kirim email verifikasi
        event(new Registered($user));

        // Auto-login (opsional)
        Auth::login($user);

        // Redirect ke notice verifikasi
        return redirect()->route('verification.notice')
                         ->with('status', 'verification-link-sent');
    }

    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'user') { // Cegah user biasa login
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun ini tidak diizinkan untuk login.',
                ]);
            }

            if ($user->role === 'admin') { // Arahkan admin ke dashboard admin
                return redirect('/admin/dashboard')->with('success', 'Login berhasil sebagai admin.');
            }

            Auth::logout();
            return back()->withErrors([
                'email' => 'Role tidak valid. Akses ditolak.',
            ]);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah!',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function settingView()
    {
        return view('admin.settings.index');
    }

    public function emailChange(Request $request)
    {
        $user = User::find(Auth::id());

        if (!$user) {
            return redirect('/dashboard')->with('error', 'User tidak ditemukan.');
        }

        $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'password_confirmation' => 'required_with:password|string|min:6|same:password'
        ]);

        $user->username = $request->username;
        $user->email = $request->email;
        $user->email_verified_at = null; // reset verifikasi email karena email diganti

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save(); // simpan dulu perubahan sebelum kirim event

        // Kirim email verifikasi
        event(new Registered($user));

        // Redirect ke halaman notifikasi verifikasi email
        return redirect()->route('verification.notice')
                         ->with('status', 'Link verifikasi email telah dikirim ke email baru.');
    }

}
