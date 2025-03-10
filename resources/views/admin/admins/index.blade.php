@extends('layouts.admin')

@section('content')
<h2 class="mt-3">{{ __('admin.list admins') }}</h2>

<a href="{{ route('admins.create') }}" class="btn btn-primary mb-3">{{ __('admin.add') }}</a>

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
                    <a href="{{ route('admins.edit', $admin->id) }}" class="btn btn-sm btn-primary">Edit</a>

                    <form action="{{ route('admins.destroy', $admin->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection
