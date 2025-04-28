<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $orders = Order::whereHas('user', function ($query) {
                $query->where('role', 'user');
            })
            ->with(['orderItems.product', 'user', 'payment'])
            ->get();

        $data = $orders->map(function ($order) {
            $items = $order->orderItems->map(function ($item) {
                return $item->product->name . ' (' . $item->quantity . ')';
            })->implode(', ');

            return [
                'ID' => $order->id,
                'User' => $order->user->username ?? 'User Tidak Ditemukan',
                'Total' => number_format($order->total_price, 2),
                'Status' => $order->payment->status ?? 'Belum Dibayar',
                'Items' => $items ?: 'Tidak Ada Item',
                'Created At' => $order->created_at->format('Y-m-d H:i:s'),
            ];
        });

        if ($data->isEmpty()) {
            return collect([]);
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'ID', 'User', 'Total', 'Status', 'Items', 'Created At'
        ];
    }
}
