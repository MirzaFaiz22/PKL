<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/product', [App\Http\Controllers\ProductController::class, 'index'])->name('produk');

Route::get('/product/create', function () {
    return view('createDevice');
});

Route::post('/products/create', [ProductController::class, 'store'])->name('products.store');


