<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\BalanceSheetController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalaryIncrementController;
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
Route::middleware(['auth','activity'])->prefix('admin')->group(function () {
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
    Route::post('/salary-increments', [SalaryIncrementController::class, 'store'])->name('salary-increments.store');
    Route::get('/balance-sheet', [BalanceSheetController::class, 'show'])->name('balance_sheet.show');

//    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
//    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
//    Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');
//    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
//    Route::post('/roles/{id}/update', [RoleController::class, 'update'])->name('roles.update');
//    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

    Route::resource('roles', RoleController::class);

    Route::get('/roles/{id}/edit-permissions', [RoleController::class, 'editPermissions'])->name('roles.edit-permissions');
    Route::post('/roles/{id}/update-permissions', [RoleController::class, 'updatePermissions'])->name('roles.update-permissions');

    Route::resource('permissions', PermissionController::class);
    //Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    //Route::post('/permissions/store', [PermissionController::class, 'store'])->name('permissions.store');
    //Route::post('/permissions/assign', [PermissionController::class, 'assignPermissions'])->name('permissions.assign');
});

