<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Admin\testController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\OrderController;

Route::get('/', [CustomerController::class, 'home'])->name('home');
Route::get('/category', [CustomerController::class, 'category'])->name('category.index');
Route::get('/category/list', [CustomerController::class, 'categoryList'])->name('category.list');
Route::get('/category/{category:slug}', [CustomerController::class, 'categoryShow'])->name('category.show');
Route::get('/book/{item:slug}', [CustomerController::class, 'show'])->name('book.show');
Route::get('/author/{author:slug}', [CustomerController::class, 'authorShow'])->name('author.show');

// Rute khusus untuk admin
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminOrderController::class, 'index'])->name('admin.dashboard.index');
    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    Route::resource('categories', CategoryController::class);
    Route::resource('authors', AuthorController::class);
    Route::resource('items', ItemController::class);
    Route::get('/profile', [ProfileController::class, 'adminEdit'])->name('admin.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'adminUpdate'])->name('admin.profile.update');
});

// Rute khusus untuk pelanggan
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/orders', [CustomerController::class, 'orderIndex'])->name('orders.index')->middleware('auth');
    Route::get('/index', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('cart.process');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add-to-cart/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/remove-from-cart', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/payment/check/{orderId}', [PaymentController::class, 'checkStatus'])->name('payment.check');
    Route::get('/payment/success/{orderId}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failure/{orderId}', [PaymentController::class, 'failure'])->name('payment.failure');
    Route::get('/payment/unfinish/{orderId}', [PaymentController::class, 'unfinish'])->name('payment.unfinish');

    Route::post('/wishlist/toggle', [CustomerController::class, 'toggleWishlist'])->name('wishlist.toggle');
    Route::get('/wishlist', [CustomerController::class, 'wishlistIndex'])->name('wishlist.index');

    // Rute untuk Midtrans
    Route::post('/payment/create', [PaymentController::class, 'createTransaction'])->name('payment.create');
    Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
});

Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Address Routes
    Route::post('/address', [ProfileController::class, 'storeAddress'])->name('address.store');
    Route::put('/address/{address}', [ProfileController::class, 'updateAddress'])->name('address.update');
    Route::delete('/address/{address}', [ProfileController::class, 'destroyAddress'])->name('address.destroy');
});
Route::get('/api/search', [App\Http\Controllers\ItemController::class, 'liveSearch'])->name('api.search');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', fn() => 'Halo Admin');
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer', fn() => 'Halo Customer');
});

Route::resource('/test', testController::class);

require __DIR__ . '/auth.php';
