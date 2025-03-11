@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>{{__('order.order detail')}} : #{{ $order->id }}</h2>
    <p><strong>{{__('order.user')}} :</strong> {{ $order->user->username ?? 'User Tidak Ditemukan' }}</p>
    <p><strong>{{__('order.total price')}} :</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
    <p><strong>Status :</strong> {{ ucfirst($order->payment->status ?? 'Belum Ada Pembayaran') }}</p>
    <p><strong>{{__('order.created at')}} :</strong> {{ $order->created_at->format('d M Y H:i') }}</p>

    <h4>{{__('order.Order Items')}}</h4>

    <table id="table" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>{{ __('order.Product') }}</th>
                <th>{{ __('order.Quantity') }}</th>
                <th>{{ __('order.Price') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Produk Tidak Ditemukan' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
