<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\WelcomePage;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AuthorController;

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () { return view('admin.admin-layout'); })->name('admin.dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('authors', AuthorController::class);
    Route::resource('items', ItemController::class);
});

Route::get('/', [WelcomePage::class,'index'])->name('home');
Route::get('/book/{item:slug}', [ItemController::class, 'show'])->name('book.show');

Route::get('/index', [PaymentController::class, 'index'])->name('payment.index');
Route::get('/admin', [AdminOrderController::class, 'index'])->name('admin.index');
