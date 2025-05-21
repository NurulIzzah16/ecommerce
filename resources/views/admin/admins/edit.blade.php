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
            <label for="role_id">{{ __('admin.role_name') }}</label>
            <select name="role_id" class="form-control" required>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">{{ __('admin.update_role') }}</button>
    </form>
@endsection
