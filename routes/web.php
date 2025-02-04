<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/product', [App\Http\Controllers\ProductController::class, 'index'])->name('produk');

Route::get('/product/create', function () {
    return view('product.createDevice');
});

Route::get('/products/get-categories', [ProductController::class, 'getCategories'])->name('products.categories');
Route::resource('products', ProductController::class)->except(['edit', 'update', 'destroy', 'show']);

// Route manual products
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');
Route::delete('/products/{product}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete');


// Routes services
Route::post('/services', [ProductController::class, 'storeService'])->name('services.store');
Route::get('/services/{service}/edit', [ProductController::class, 'editService'])->name('services.edit');
Route::put('/services/{service}', [ProductController::class, 'updateService'])->name('services.update');
Route::delete('/services/{service}', [ProductController::class, 'destroyService'])->name('services.destroy');
Route::get('/services/{service}', [ProductController::class, 'showService'])->name('services.show');


