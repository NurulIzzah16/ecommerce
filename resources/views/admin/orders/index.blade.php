@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2 class="mt-3">{{__('order.list orders')}}</h2>

        <table id="table" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>{{ __('order.user') }}</th>
                    <th>{{ __('order.total price') }}</th>
                    <th>Status</th>
                    <th>{{ __('order.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->username ?? 'User Tidak Ditemukan' }}</td>
                        <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td>{{ $order->payment->status ?? 'Belum Dibayar' }}</td>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
