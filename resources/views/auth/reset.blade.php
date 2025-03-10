@extends('layouts.auth')

@section('content')
    <div class="row">
       @extends('layouts.auth')

@section('content')
    <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">

            <div class="card card-primary">
                <div class="card-header">
                    <h4>{{ __('auth.reset_password') }}</h4>
                </div>

                <div class="card-body">
                    <p class="text-muted">{{ __('auth.reset_password') }}</p>

                    {{-- Form Reset Password --}}
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        {{-- Token Reset Password --}}
                        <input type="hidden" name="token" value="{{ $request->token }}">

                        {{-- Input Email (Hidden) --}}
                        <input type="hidden" name="email" value="{{ $request->email }}">

                        {{-- Input Password Baru --}}
                        <div class="form-group">
                            <label for="password">{{ __('auth.new_password') }}</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="form-group">
                            <label for="password_confirmation">{{ __('auth.confirm_password') }}</label>
                            <input id="password_confirmation" type="password"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                name="password_confirmation" required>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                Reset Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
<div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h4>Reset Password</h4>
                </div>

                <div class="card-body">
                    <p class="text-muted">Reset your password here</p>

                    {{-- Form Reset Password --}}
                    <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                        {{-- Token Reset Password --}}
                        <input type="hidden" name="token" value="{{ $request->token }}">

                        {{-- Input Email (Hidden) --}}
                        <input type="hidden" name="email" value="{{ $request->email }}">

                        {{-- Input Password Baru --}}
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="form-group">
                            <label for="password_confirmation">Confirm New Password</label>
                            <input id="password_confirmation" type="password"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                name="password_confirmation" required>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                Reset Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
