@extends('layouts.admin')

@section('content')
    <h2 class="mt-3">{{__('notification.Notifications')}}</h2>

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

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($notifications->count())
        <ul class="list-group">
            @foreach ($notifications as $notification)
                <li class="list-group-item d-flex justify-content-between align-items-center
                    {{ is_null($notification->read_at) ? 'list-group-item-info' : '' }}">
                    <div>
                        @if($notification->type === 'App\Notifications\NewUserRegistered')
                            New user registered: {{ $notification->data['name'] }}
                        @elseif($notification->type === 'App\Notifications\NewOrder')
                            New order placed: Order #{{ $notification->data['order_id'] }}
                        @elseif($notification->type === 'App\Notifications\OrderStatusChanged')
                            Order #{{ $notification->data['order_id'] }} status changed to {{ $notification->data['status'] }}
                        @endif
                    </div>

                    <div class="d-flex align-items-center">
                        @if (is_null($notification->read_at))
                        <a href="{{ route('admin.notifications.read', $notification->id) }}" class="btn btn-sm btn-primary mr-2">View</a>
                        @endif

                        {{-- Tombol hapus notifikasi --}}
                        <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" type="submit">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="mt-3">
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>
    @else
        <p>No notifications found.</p>
    @endif

@endsection
