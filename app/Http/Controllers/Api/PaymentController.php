<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Exception;

class PaymentController extends Controller
{
    public function handleNotification(Request $request)
    {
        try {
            $payload = $request->getContent();
            $notification = json_decode($payload);

            if (!$notification || !isset($notification->transaction_status)) {
                return response()->json(['message' => 'Notifikasi tidak valid.'], 400);
            }

            // Ambil data dari notifikasi Midtrans
            $transactionStatus = $notification->transaction_status;
            $orderId = str_replace('ORDER-', '', $notification->order_id); // Hilangkan prefix 'ORDER-' untuk mendapatkan ID asli
            $paymentMethod = $notification->payment_type;
            $transactionId = $notification->transaction_id;

            // Cari data payment di database
            $payment = Payment::where('order_id', $orderId)->first();

            if (!$payment) {
                return response()->json(['message' => 'Pembayaran tidak ditemukan.'], 404);
            }

            // Update payment berdasarkan status dari Midtrans
            if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
                $payment->status = 'success';
                $payment->transaction_id = $transactionId;
                $payment->payment_method = $paymentMethod;
            } elseif ($transactionStatus === 'pending') {
                $payment->status = 'pending';
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $payment->status = 'failed';
            }

            $payment->save();

            return response()->json(['message' => 'Notifikasi diproses dengan sukses.'], 200);

        } catch (Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

}
