<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
        $this->middleware('permission:admins')->only(['index']);
        $this->middleware('permission:admins.create')->only(['create', 'store']);
        $this->middleware('permission:admins.edit')->only(['edit', 'update']);
        $this->middleware('permission:admins.delete')->only(['destroy']);
    }

    public function index()
    {
        $admins = User::where('role', 'admin')->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.admins.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('admins.index')->with('success', 'Admin berhasil dibuat.');
    }

    public function edit($id)
    {
        $admin = User::findOrFail($id);
        $roles = Role::all(); // Ambil semua data role dari tabel roles
        return view('admin.admins.edit', compact('admin', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:50',
            'role_id' => 'required|exists:roles,id', // Validasi role_id
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $admin->username = $request->username;
        $admin->role_id = $request->role_id; // Update role_id

        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('admins.index')->with('success', 'Admin berhasil diupdate.');
    }

    public function destroy($id)
    {
        $admin = User::findOrFail($id);
        $admin->delete();

        return redirect()->route('admins.index')->with('success', 'Admin berhasil dihapus.');
    }
}
