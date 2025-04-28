@extends('layouts.admin')

@section('content')
    <h2 class="mt-3">{{__('user.list users')}}</h2>
    <div class="mb-3">
        <a href="{{ route('users.export') }}" class="btn btn-success">Export</a>
    </div>
    <table id="table" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>{{ __('user.name') }}</th>
                <th>Email</th>
                <th>{{ __('user.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-info">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
