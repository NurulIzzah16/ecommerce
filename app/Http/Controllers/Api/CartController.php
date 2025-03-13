<?php
namespace App\Http\Controllers\Api;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function apiAddToCart(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $user = Auth::user();

            $cartItem = CartItem::where('user_id', $user->id)
                                ->where('product_id', $request->product_id)
                                ->first();

            if ($cartItem) {
                $cartItem->quantity += $request->quantity;
                $cartItem->save();
            } else {
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                ]);
            }

            return response()->json([
                'message' => 'Produk berhasil ditambahkan ke keranjang.',
                'username' => $user->username
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Data yang dimasukkan tidak valid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function apiGetCart(Request $request)
    {
        try {
            $user = Auth::user();

            // Ambil semua item keranjang untuk pengguna yang sedang login
            $cartItems = CartItem::with('product') // Termasuk relasi ke produk
                ->where('user_id', $user->id)
                ->get();

            if ($cartItems->isEmpty()) {
                return response()->json(['message' => 'Keranjang belanja Anda kosong.'], 200);
            }

            // Menampilkan isi keranjang
            $cartData = $cartItems->map(function($cartItem) {
                return [
                    'product_id' => $cartItem->product->id,
                    'product_name' => $cartItem->product->name,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                    'total' => $cartItem->product->price * $cartItem->quantity,
                ];
            });

            return response()->json([
                'message' => 'Data keranjang berhasil ditemukan.',
                'cart' => $cartData
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function apiUpdateCartQuantity(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $user = Auth::user();

            $cartItem = CartItem::where('user_id', $user->id)
                                ->where('product_id', $request->product_id)
                                ->first();

            if (!$cartItem) {
                return response()->json(['message' => 'Produk tidak ditemukan di keranjang.'], 404);
            }

            $cartItem->quantity = $request->quantity;
            $cartItem->save();

            return response()->json([
                'message' => 'Quantity produk berhasil diperbarui.',
                'username' => $user->username
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Data yang dimasukkan tidak valid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function apiRemoveFromCart(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
            ]);

            $user = Auth::user();

            $cartItem = CartItem::where('user_id', $user->id)
                                ->where('product_id', $request->product_id)
                                ->first();

            if (!$cartItem) {
                return response()->json(['message' => 'Produk tidak ditemukan di keranjang.'], 404);
            }

            $cartItem->delete();

            return response()->json([
                'message' => 'Produk berhasil dihapus dari keranjang.',
                'username' => $user->username
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Data yang dimasukkan tidak valid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
