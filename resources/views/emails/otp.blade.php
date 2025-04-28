<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
</head>
<body>
    <h2>Halo, {{ $user->username }}!</h2>
    <p>Terima kasih telah mendaftar.</p>
    <p>Kode OTP verifikasi kamu adalah:</p>
    <h1>{{ $user->otp_code }}</h1>
    <p>Kode ini akan kedaluwarsa pada: <strong>{{ \Carbon\Carbon::parse($user->otp_expires_at)->format('H:i') }}</strong></p>
</body>
</html>
