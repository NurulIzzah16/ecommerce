@extends('layouts.admin')

@section('content')
    <h2 class="mt-3">{{ __('admin.edit_admin') }}</h2>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admins.update', $admin->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="username">{{ __('admin.username') }}</label>
            <input type="text" name="username" class="form-control" value="{{ old('username', $admin->username) }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $admin->email) }}" readonly>
        </div>

        <div class="form-group">
            <label for="role">{{ __('admin.role_name') }}</label>
            <select name="role" class="form-control" required>
                <option value="admin" {{ $admin->role === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ $admin->role === 'user' ? 'selected' : '' }}>User</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">{{ __('admin.update_role') }}</button>
    </form>
@endsection
