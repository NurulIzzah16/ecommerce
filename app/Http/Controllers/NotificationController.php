<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $notifications = $user->notifications()->paginate(10);
        $unreadCount = $user->unreadNotifications()->count();

        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);

        // Tandai sebagai sudah dibaca
        $notification->markAsRead();

        // Redirect ke halaman sesuai tipe notifikasi
        switch ($notification->type) {
            case 'App\Notifications\NewUserRegistered':
                return redirect()->route('users.show', $notification->data['user_id']);

            case 'App\Notifications\NewOrder':
            case 'App\Notifications\OrderStatusChanged':
                return redirect()->route('orders.show', $notification->data['order_id']);

            default:
                return redirect()->back()->with('error', 'Unknown notification type.');
        }
    }

    public function destroy($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->delete();

        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    public function show($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);

        // Tandai sebagai sudah dibaca
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return view('admin.notifications.show', compact('notification'));
    }

    public function updateStatus($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);

        // Tandai notifikasi sebagai sudah dibaca
        $notification->markAsRead();

        // Cek apakah notifikasi tersebut adalah perubahan status order
        if ($notification->type === 'App\Notifications\OrderStatusChanged') {
            // Arahkan pengguna ke halaman detail order sesuai dengan order_id
            $orderId = $notification->data['order_id'];
            return redirect()->route('orders.show', $orderId);
        }

        return redirect()->back()->with('error', 'Tipe notifikasi tidak dikenal.');
    }


}
