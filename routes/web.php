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
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\NotificationController;

// Redirect ke halaman login
Route::get('/', function () {
    return redirect('/login');
});

// Register & Login
Route::get('/register', [AuthenticationController::class, 'registerForm'])->name('registerForm');
Route::post('/register', [AuthenticationController::class, 'register'])->name('register');
Route::get('/login', [AuthenticationController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthenticationController::class, 'login']);


// Middleware Admin (untuk admin)
Route::middleware(['auth','verified', AdminMiddleware::class])->group(function () {

    // Admin Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/profile', [UserController::class, 'profile'])->name('profile');

    // Admin Management
    Route::get('admins', [AdminController::class, 'index'])->name('admins.index');
    Route::get('admins/create', [AdminController::class, 'create'])->name('admins.create');
    Route::post('admins', [AdminController::class, 'store'])->name('admins.store');
    Route::get('admins/{id}/edit', [AdminController::class, 'edit'])->name('admins.edit');
    Route::put('admins/{id}', [AdminController::class, 'update'])->name('admins.update');
    Route::delete('admins/{id}', [AdminController::class, 'destroy'])->name('admins.destroy');

    // Roles
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::get('roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
    Route::put('roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // Management
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/export', [OrderController::class, 'export'])->name('orders.export');
    Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

    // Categories & Products
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('products', ProductController::class)->except(['show']);

    // Settings
    Route::get('settings', [AuthenticationController::class, 'settingView'])->name('settings.index');
    Route::post('settings/email', [AuthenticationController::class, 'emailChange'])->name('settings.store');

    // Export & Import
    Route::get('/categories/export', [CategoryController::class, 'export'])->name('categories.export');
    Route::post('/categories/import', [CategoryController::class, 'import'])->name('categories.import');
    Route::get('/categories/template', [CategoryController::class, 'downloadTemplate'])->name('categories.downloadTemplate');
    Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
    Route::get('/products/template', [ProductController::class, 'downloadTemplate'])->name('products.downloadTemplate');
    Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('admin.notifications.read');
    Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('admin.notifications.show');
    Route::get('/notifications/{id}/update-status', [NotificationController::class, 'updateStatus'])->name('notifications.updateStatus');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('admin.notifications.destroy');

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
