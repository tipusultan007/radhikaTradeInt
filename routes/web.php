<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index']);
    Route::get('/', [HomeController::class, 'index']);
});
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::resource('accounts', AccountController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('products', ProductController::class);
    Route::resource('expense_categories', ExpenseCategoryController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('sales', SaleController::class);
    Route::resource('warehouses', WarehouseController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::resource('users', UserController::class);
    Route::resource('assets', AssetController::class);
    Route::resource('payroll', PayrollController::class);
    Route::get('/journals', [JournalController::class, 'index'])->name('journals.index');
    Route::get('/warehouse-info', [WarehouseController::class, 'getWarehouseInfo'])->name('warehouse.info');
});

