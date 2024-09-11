<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
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
    // Route::resource('companies', CompanyController::class);

    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('companies.show');
    Route::get('/companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::patch('/companies/{company}', [CompanyController::class, 'update'])->name('companies.update');
    Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');

    Route::get('/products/{type?}', [ProductController::class, 'index'])->name('products');
    Route::get('/products.add}', [ProductController::class, 'add'])->name('products.add');
    Route::post('/products/upload', [ProductController::class, 'upload'])->name('products.upload');

    Route::post('/update-cell', [ProductController::class, 'updateCell'])->name('updateCell');
    Route::post('/product/can-view', [ProductController::class, 'canView'])->name('product.canView');
    Route::post('/product/add-field', [ProductController::class, 'addField'])->name('product.addField');
    Route::post('/products/{productId}/delete-field', [ProductController::class, 'deleteField'])->name('products.deleteField');
    Route::post('/product/update-field', [ProductController::class, 'updateField'])->name('product.updateField');

    Route::get('/product/filter', [ProductController::class, 'filter'])->name('product.filter');
});

Route::middleware(['auth','nl_admin'])->group(function () {
    Route::resource('companies', CompanyController::class);
});

Route::middleware(['auth','admin'])->group(function () {
    Route::resource('users', UserController::class);
});

require __DIR__ . '/auth.php';

// admin使う場合：
// Route::get('/admin-only', function () {
// })->middleware('admin');