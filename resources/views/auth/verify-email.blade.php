@extends('layouts.auth')

@section('content')
    <div class="row">

        <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h4>Email Verification</h4>
                </div>

                <div class="card-body">
                    @if (session('status') == 'verification-link-sent')
                        <div class="mb-4 text-sm text-success">
                            Kami telah mengirimkan tautan verifikasi ke email Anda!
                        </div>
                    @endif

                    <p class="text-muted">Silakan cek email Anda dan klik tautan verifikasi untuk mengaktifkan akun.</p>

                    {{-- Tombol Kirim Ulang Email Verifikasi --}}
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                Kirim Ulang Verifikasi
                            </button>
                        </div>
                    </form>

                    {{-- Tombol Logout --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <div class="form-group">
                            <button type="submit" class="btn btn-secondary btn-lg btn-block">
                                Log Out
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @endsection
