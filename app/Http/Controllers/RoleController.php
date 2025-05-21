<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:roles')->only(['index']);
        $this->middleware('permission:roles.create')->only(['create', 'store']);
        $this->middleware('permission:roles.edit')->only(['edit', 'update']);
        $this->middleware('permission:roles.delete')->only(['destroy']);
    }

    // Tampilkan semua role
    public function index()
    {
        $roles = Role::all(); // tidak perlu load relasi karena tidak ada
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        // Daftar permissions hardcoded, sesuaikan dengan yang kamu punya
        $permissions = [
            "categories",
            "categories.create",
            "categories.edit",
            "categories.delete",
            "products",
            "products.create",
            "products.edit",
            "products.delete",
            "users",
            "admins",
            "admins.create",
            "admins.edit",
            "admins.delete",
            "orders",
            "roles",
            "roles.create",
            "roles.edit",
            "roles.delete"
        ];

        // Group permissions by prefix modul
        $groupedPermissions = collect($permissions)->groupBy(function($perm) {
            return explode('.', $perm)[0];
        });

        return view('admin.roles.create', ['permissions' => $groupedPermissions]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array|nullable',
            'permissions.*' => 'string',
        ]);

        $role = new Role();
        $role->name = $request->name;

        // Simpan permissions sebagai JSON string
        $role->permissions = json_encode($request->permissions ?? []);
        $role->save();

        return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        $permissions = [
            "categories",
            "categories.create",
            "categories.edit",
            "categories.delete",
            "products",
            "products.create",
            "products.edit",
            "products.delete",
            "users",
            "admins",
            "admins.create",
            "admins.edit",
            "admins.delete",
            "orders",
            "roles",
            "roles.create",
            "roles.edit",
            "roles.delete"
        ];

        $groupedPermissions = collect($permissions)->groupBy(function($perm) {
            return explode('.', $perm)[0];
        });

        return view('admin.roles.edit', [
            'role' => $role,
            'permissions' => $groupedPermissions
        ]);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array|nullable',
            'permissions.*' => 'string',
        ]);

        $role->name = $request->name;
        $role->permissions = json_encode($request->permissions ?? []);
        $role->save();

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
    }
}
