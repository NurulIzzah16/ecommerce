@extends('auth.layout')

@section('content')
<div class="container">
    <h2>{{ __('auth.login') }}</h2>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">{{ __('auth.password') }}</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <a href="{{ route('password.request') }}" class="text-small">{{ __('auth.forgot_password') }}?
            </a>
        </div>

        <button type="submit" class="btn btn-primary">{{ __('auth.login') }}</button>
    </form>
</div>
@endsection
