<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Menampilkan daftar user (hanya untuk admin)
     */
    public function index()
    {
        $user = Auth::user();

        // Jika bukan admin, tolak akses
        if ($user->role !== 'admin') {
            abort(403, 'Anda tidak diizinkan mengakses halaman ini.');
        }

        // Admin bisa melihat semua user
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan profil user
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $authUser = Auth::user();

        // User biasa hanya boleh melihat profil sendiri
        if ($authUser->role !== 'admin' && $authUser->id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat profil ini.');
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Menampilkan halaman edit profil user (hanya untuk user sendiri)
     */
    public function edit()
    {
        return view('admin.users.edit', ['user' => Auth::user()]);
    }

    /**
     * Memperbarui profil user
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user(); // Pastikan ini instance dari User

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:50',
            'email'    => 'required|email|max:100|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Set nilai baru ke model User
        $user->username = $request->username;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save(); // Pastikan ini dipanggil pada instance User

        return redirect()->route('users.show', $user->id)->with('success', 'Profil berhasil diperbarui.');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('admin.users.profile', compact('user'));
    }
}
