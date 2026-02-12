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

Route::get('/', [CustomerController::class, 'home'])->name('home');
Route::get('/category', [CustomerController::class, 'category'])->name('category.index');
Route::get('/category/list', [CustomerController::class, 'categoryList'])->name('category.list');
Route::get('/category/{id}', [CustomerController::class, 'categoryShow'])->name('category.show');
Route::get('/book/{item:slug}', [CustomerController::class, 'show'])->name('book.show');

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminOrderController::class, 'index'])->name('admin.dashboard.index');
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

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', fn() => 'Halo Admin');
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer', fn() => 'Halo Customer');
});

Route::resource('/test', testController::class);

require __DIR__ . '/auth.php';