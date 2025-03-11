@extends('layouts.admin')

@section('content')
    <h2 class="mt-3">{{__('dashboard.admin dashboard')}}</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">{{__('dashboard.total users')}}</div>
                <div class="card-body">
                    <h5 class="card-title">{{ \App\Models\User::where('role', 'user')->count() }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">{{__('dashboard.total products')}}</div>
                <div class="card-body">
                    <h5 class="card-title">{{ \App\Models\Product::count() }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">{{__('dashboard.total orders')}}</div>
                <div class="card-body">
                    <h5 class="card-title">{{ \App\Models\Order::count() }}</h5>
                </div>
            </div>
        </div>
    </div>

    <h4>{{__('dashboard.recent orders')}}</h4>
    <table id="table" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>{{__('dashboard.user')}}</th>
                <th>{{__('dashboard.total price')}}</th>
                <th>Status</th>
                <th>{{__('dashboard.created at')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentOrders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->username ?? 'Pengguna Tidak Ditemukan' }}</td>
                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($order->payment->status ?? 'Tidak Diketahui') }}</td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
