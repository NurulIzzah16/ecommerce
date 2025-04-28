<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Order #{{ $order->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .content { margin: 0 30px; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <h2>INVOICE</h2>
        <p>Order ID: {{ $order->id }}</p>
    </div>

    <div class="content">
        <p>Nama Pelanggan: {{ $order->user->username }}</p>
        <p>Tanggal Pesanan: {{ $order->created_at->format('d M Y') }}</p>
        <p>Status Pembayaran: {{ $order->payment->status }}</p>
        <p>Total Pembayaran: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
    </div>

    <div class="footer">
        <p>Terima kasih telah berbelanja di toko kami!</p>
    </div>
</body>
</html>
