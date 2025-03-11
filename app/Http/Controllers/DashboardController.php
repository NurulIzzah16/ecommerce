<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

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

        return view('admin.dashboard', compact('recentOrders'));
    }
}
