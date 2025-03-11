<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderExport;
use PDF;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        // Mengambil semua pesanan dari user biasa (bukan admin)
        $orders = Order::with(['user', 'payment'])
                    ->whereHas('user', function($query) {
                        $query->where('role', '!=', 'admin');
                    })
                    ->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'orderItems.product', 'payment'])->find($id);

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Pesanan tidak ditemukan.');
        }

        return view('admin.orders.show', compact('order'));
    }

}
