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
});

Route::get('/', [WelcomePage::class,'index'])->name('home');

Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/create', [ItemController::class,'create'])->name('items.create');
Route::post('/items', [ItemController::class, 'store'])->name('items.store');
Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');

Route::get('/index', [PaymentController::class, 'index'])->name('payment.index');
Route::get('/admin', [AdminOrderController::class, 'index'])->name('admin.index');
