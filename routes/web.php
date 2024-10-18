<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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



    Route::post('/update-cell', [ProductController::class, 'updateCell'])->name('updateCell');
    Route::post('/called-change', [ProductController::class, 'calledChange'])->name('calledChange');
    Route::post('/product/can-view', [ProductController::class, 'canView'])->name('product.canView');
    Route::post('/product/add-field', [ProductController::class, 'addField'])->name('product.addField');
    Route::post('/products/{productId}/delete-field', [ProductController::class, 'deleteField'])->name('products.deleteField');
    Route::post('/product/update-field', [ProductController::class, 'updateField'])->name('product.updateField');

    Route::get('/product/filter', [ProductController::class, 'filter'])->name('product.filter');

    Route::get('/products/{id}/download-csv', [ProductController::class, 'downloadCSV'])->name('product.downloadCSV');

    Route::get('/products/nolist', [ProductController::class, 'index'])->name('product.nolist');


});

Route::middleware(['auth','nl_admin'])->group(function () {
    Route::resource('companies', CompanyController::class);
    Route::post('/products/update-companies', [ProductController::class, 'update_companies'])->name('product.updateCompanies');
    Route::get('/products.add}', [ProductController::class, 'add'])->name('products.add');
    Route::post('/products/upload', [ProductController::class, 'upload'])->name('products.upload');
    Route::delete('/products/{id}/delete', [ProductController::class, 'delete'])->name('product.delete');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

Route::middleware(['auth','admin'])->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');

});

require __DIR__ . '/auth.php';

// admin使う場合：
// Route::get('/admin-only', function () {
// })->middleware('admin');