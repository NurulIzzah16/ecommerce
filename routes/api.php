<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\NotificationController;

// API Register & Login
Route::post('/register', [AuthenticationController::class, 'apiRegister']);
Route::post('/login', [AuthenticationController::class, 'apiLogin']);
Route::post('/verify-otp', [AuthenticationController::class, 'verifyOtp']);
Route::post('/forgot-password/request', [AuthenticationController::class, 'requestOtpResetPassword']);
Route::post('/forgot-password/reset', [AuthenticationController::class, 'resetPasswordWithOtp']);

// Group route yang butuh login dan email terverifikasi
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthenticationController::class, 'apiLogout']);
    Route::put('/update-data', [AuthenticationController::class, 'apiUpdatedata']);
    Route::get('/user', [UserController::class, 'getUserData']);
    Route::get('/products', [ProductController::class, 'apiProducts']);

    // Cart
    Route::post('/cart/add', [CartController::class, 'apiAddToCart']);
    Route::delete('/cart/remove', [CartController::class, 'apiRemoveFromCart']);
    Route::put('/cart/update', [CartController::class, 'apiUpdateCartQuantity']);
    Route::get('/cart', [CartController::class, 'apiGetCart']);

    // Checkout dan Payment
    Route::post('/checkout', [CheckoutController::class, 'apiCheckout']);
    Route::post('/midtrans/notification', [PaymentController::class, 'handleNotification']);

    // Notifikasi
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
});
