<?php
namespace App\Http\Controllers\Api;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function apiCheckout(Request $request)
{
    try {
        $user = Auth::user();

        $cartItems = CartItem::with('product')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Keranjang belanja Anda kosong.'], 400);
        }

        $totalPrice = 0;

        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;

            if ($product->stock < $cartItem->quantity) {
                return response()->json([
                    'message' => 'Stok produk ' . $product->name . ' tidak mencukupi.'
                ], 400);
            }

            $totalPrice += $product->price * $cartItem->quantity;
        }

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $totalPrice
        ]);

        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $product->price,
            ]);

            $product->decreaseStock($cartItem->quantity);
            $cartItem->delete();
        }

        // Midtrans Configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => $order->id,
                'gross_amount' => $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'enabled_payments' => ['qris', 'bank_transfer', 'credit_card']
        ];

        $snapToken = Snap::getSnapToken($params);

        // Simpan pembayaran di tabel payments
        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'user_id' => $user->id,
                'transaction_id' => 'PENDING', // Nilai sementara, nanti akan diperbarui
                'amount' => $order->total_price,
                'payment_method' => 'midtrans', // Ini akan di-update setelah payment success
                'snap_token' => $snapToken,
                'status' => 'pending'
            ]
        );

        return response()->json([
            'message' => 'Checkout berhasil dilakukan.',
            'order' => $order,
            'snap_token' => $snapToken
        ], 200);

    } catch (ModelNotFoundException $e) {
        return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
    } catch (Exception $e) {
        return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
    }
}

}
