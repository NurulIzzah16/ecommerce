@extends('layouts.admin')

@section('content')
    <h2 class="mt-3">{{ __('setting.change_setting') }}</h2>

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

    <form action="{{ route('settings.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="email">{{ __('setting.new_email') }}</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group mt-3">
            <label for="username">{{ __('setting.new_username') }}</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="form-group mt-3">
            <label for="password">{{ __('setting.new_password') }}</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group mt-3">
            <label for="password_confirmation">{{ __('setting.confirm_password') }}</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary mt-3">{{ __('setting.save') }}</button>
    </form>
@endsection
