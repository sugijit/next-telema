<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 架電リスト
    Route::resource('products', ProductController::class);
    Route::get('/products/{type?}', [ProductController::class, 'index'])->name('products');
    Route::get('/products.add}', [ProductController::class, 'add'])->name('products.add');
    Route::post('/products/upload', [ProductController::class, 'upload'])->name('products.upload');

    Route::post('/update-cell', [ProductController::class, 'updateCell'])->name('updateCell');
    Route::post('/product/can-view', [ProductController::class, 'canView'])->name('product.canView');
    Route::post('/product/add-field', [ProductController::class, 'addField'])->name('product.addField');
});


Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', UserController::class);
});





require __DIR__ . '/auth.php';










// admin使う場合：
// Route::get('/admin-only', function () {
// })->middleware('admin');