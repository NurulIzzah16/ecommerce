<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\PaymentController;



// Route untuk API Registration & Login
Route::post('/register', [AuthenticationController::class, 'apiRegister']);
Route::post('/login', [AuthenticationController::class, 'apiLogin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticationController::class, 'apiLogout']);
    Route::put('/update-data', [AuthenticationController::class, 'apiUpdatedata']);
    Route::get('/user', [UserController::class, 'getUserData']);
    Route::get('/products', [ProductController::class, 'apiProducts']);
    Route::post('/cart/add', [CartController::class, 'apiAddToCart']);
    Route::delete('/cart/remove', [CartController::class, 'apiRemoveFromCart']);
    Route::put('/cart/update', [CartController::class, 'apiUpdateCartQuantity']);
    Route::get('/cart', [CartController::class, 'apiGetCart']);
    Route::post('/checkout', [CheckoutController::class, 'apiCheckout']);
    Route::post('/midtrans/notification', [PaymentController::class, 'handleNotification']);

});

