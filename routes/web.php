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
use App\Http\Middleware\Permission;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;

Route::get('/', function () {
    return redirect('/login');
});
Route::get('/register', [AuthenticationController::class, 'registerForm'])->name('registerForm');
Route::post('/register', [AuthenticationController::class, 'register'])->name('register');
Route::get('/login', [AuthenticationController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthenticationController::class, 'login']);

Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::resource('categories', CategoryController::class);

Route::resource('products', ProductController::class);

Route::get('users', [UserController::class, 'index'])->name('users.index');
Route::get('users/{id}', [UserController::class, 'show'])->name('users.show');
Route::get('/export/user', [UserController::class, 'export'])->name('export.user');

Route::get('/admins', [AdminController::class, 'index'])->name('admins.index');
Route::get('admins/data', [AdminController::class, 'show'])->name('admins.data');
Route::get('admins/create', [AdminController::class, 'create'])->name('admins.create');
Route::post('admins', [AdminController::class, 'store'])->name('admins.store');
Route::get('admins/{id}', [AdminController::class, 'edit'])->name('admins.edit');
Route::delete('admins/{id}', [AdminController::class, 'show'])->name('admins.destroy');
Route::get('/search-role', [AdminController::class, 'search'])->name('search.role');
Route::put('admins/{id}', [AdminController::class, 'update'])->name('admins.update');

Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');
Route::get('orders/{id}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.invoice');
Route::get('/export/order', [OrderController::class, 'export'])->name('export.order');

Route::get('settings', [AuthenticationController::class, 'settingView'])->name('settings.index');
Route::post('settings/email', [AuthenticationController::class, 'emailChange'])->name('settings.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
});
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admins', [AdminController::class, 'index'])->name('admins.index');
});

Route::get('/greeting/{locale}', function (string $locale) {
    if (! in_array($locale, ['en', 'id'])) {
        abort(400);
    }
    App::setLocale($locale);
    session()->put('locale', $locale);
    return back();
})->name('set.language');
Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');

Route::get('/profile', [UserController::class, 'profile'])->name('profile');
