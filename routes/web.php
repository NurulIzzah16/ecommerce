<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;

// Redirect ke halaman login
Route::get('/', function () {
    return redirect('/login');
});

// Register & Login
Route::get('/register', [AuthenticationController::class, 'registerForm'])->name('registerForm');
Route::post('/register', [AuthenticationController::class, 'register'])->name('register');
Route::get('/login', [AuthenticationController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthenticationController::class, 'login']);

// Middleware Auth (untuk user)
Route::middleware(['auth'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
});

// Middleware Admin (untuk admin)
Route::middleware(['auth', AdminMiddleware::class])->group(function () {

    // Admin Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Admin Management
    Route::resource('admins', AdminController::class);

    // Orders Management
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('orders/{id}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.invoice');
    Route::get('/export/order', [OrderController::class, 'export'])->name('export.order');

    // Categories & Products
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);

     // Profil Pengguna (semua user termasuk admin)
     Route::get('/profile', [UserController::class, 'profile'])->name('profile');

     // Settings
     Route::get('settings', [AuthenticationController::class, 'settingView'])->name('settings.index');
     Route::post('settings/email', [AuthenticationController::class, 'emailChange'])->name('settings.store');

     // Logout
     Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');
});

// Localization
Route::get('/greeting/{locale}', function (string $locale) {
    if (!in_array($locale, ['en', 'id'])) {
        abort(400);
    }
    App::setLocale($locale);
    session()->put('locale', $locale);
    return back();
})->name('set.language');
