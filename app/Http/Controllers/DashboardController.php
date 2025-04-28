<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $recentOrders = Order::with(['user', 'payment'])
            ->whereHas('user', function ($query) {
                $query->where('role', 'user');
            })
            ->latest()
            ->take(5)
            ->get();

        $admin = Auth::user(); // ambil admin yang sedang login
        $unreadCount = $admin->unreadNotifications->count();
        $notifications = $admin->notifications;

        return view('admin.dashboard', compact('recentOrders', 'unreadCount', 'notifications'));
    }
}
