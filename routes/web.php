<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\WelcomePage;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\CartController;

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () { return view('admin.admin-layout'); })->name('admin.dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('authors', AuthorController::class);
    Route::resource('items', ItemController::class);
});


Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/add-to-cart/{id}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/remove-from-cart', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/', [WelcomePage::class,'index'])->name('home');
Route::get('/book/{item:slug}', [ItemController::class, 'show'])->name('book.show');

Route::get('/index', [PaymentController::class, 'index'])->name('payment.index');
Route::get('/admin', [AdminOrderController::class, 'index'])->name('admin.index');
