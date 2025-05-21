@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Buat Peran Baru</h2>
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf

        <!-- Input Nama Peran -->
        <div class="form-group row mb-3">
            <label for="name" class="col-md-4 col-form-label text-md-right">Nama Peran</label>
            <div class="col-md-8">
                <input type="text" class="form-control" id="name" name="name" required autofocus>
            </div>
        </div>

        <!-- Pilih Permissions -->
        <div class="form-group row">
            <label class="col-md-4 col-form-label text-md-right">Izin</label>
            <div class="col-md-8">
                @php
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
                    $grouped = [];
                    foreach ($permissions as $perm) {
                        // prefix sebelum titik, kalau gak ada titik, prefix = perm itu sendiri
                        $parts = explode('.', $perm);
                        $prefix = $parts[0];
                        $grouped[$prefix][] = $perm;
                    }
                @endphp

                @foreach ($grouped as $module => $perms)
                    <div class="mb-3">
                        <strong>{{ ucfirst($module) }}</strong>
                        <div style="margin-left: 1.5rem;">
                            @foreach ($perms as $perm)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        name="permissions[]" value="{{ $perm }}"
                                        id="perm-{{ str_replace('.', '_', $perm) }}">
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
                <button type="submit" class="btn btn-primary">Buat Peran</button>
            </div>
        </div>
    </form>
</div>
@endsection
