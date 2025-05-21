@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Edit Peran: {{ $role->name }}</h2>
    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Input Nama Peran -->
        <div class="form-group row mb-3">
            <label for="name" class="col-md-4 col-form-label text-md-right">Nama Peran</label>
            <div class="col-md-8">
                <input type="text" class="form-control" id="name" name="name" value="{{ $role->name }}" required autofocus>
            </div>
        </div>

        <!-- Pilih Permissions -->
        <div class="form-group row">
            <label class="col-md-4 col-form-label text-md-right">Izin</label>
            <div class="col-md-8">
                @php
                    // Ambil permissions dari role, asumsikan disimpan sebagai JSON di db
                    $rolePermissions = json_decode($role->permissions, true) ?? [];

                    // List permission dari controller (array string)
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

                    // Group permission berdasar prefix modul
                    $grouped = [];
                    foreach ($permissions as $perm) {
                        $parts = explode('.', $perm);
                        $prefix = $parts[0];
                        $grouped[$prefix][] = $perm;
                    }
                @endphp

                @foreach($grouped as $module => $perms)
                    <div class="mb-3">
                        <strong>{{ ucfirst($module) }}</strong>
                        <div style="margin-left: 1.5rem;">
                            @foreach($perms as $perm)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        name="permissions[]" value="{{ $perm }}"
                                        id="perm-{{ str_replace('.', '_', $perm) }}"
                                        {{ in_array($perm, $rolePermissions) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm-{{ str_replace('.', '_', $perm) }}">
                                        @if(str_contains($perm, '.'))
                                            {{ ucfirst(explode('.', $perm)[1]) }}
                                        @else
                                            {{ ucfirst($perm) }}
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="form-group row mb-0">
            <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-primary">Perbarui Peran</button>
            </div>
        </div>
    </form>
</div>
@endsection
