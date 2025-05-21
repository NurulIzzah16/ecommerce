@extends('layouts.admin')

@section('content')
<h2 class="mt-3">{{ __('admin.list_admins') }}</h2>

@if (!in_array('admins.create', auth()->user()->role->permissions ?? []))
<a href="{{ route('admins.create') }}" class="btn btn-primary mb-3">{{ __('admin.add') }}</a>
@endif
<table id="adminTable" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>{{ __('user.username') }}</th>
            <th>Email</th>
            <th>{{ __('user.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($admins as $admin)
            <tr>
                <td>{{ $admin->id }}</td>
                <td>{{ $admin->username }}</td>
                <td>{{ $admin->email }}</td>
                <td>
                    @if (!in_array('admins.edit', auth()->user()->role->permissions ?? []))
                    <a href="{{ route('admins.edit', $admin->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    @endif
                    @if (!in_array('admins.delete', auth()->user()->role->permissions ?? []))
                    <form action="{{ route('admins.destroy', $admin->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection
