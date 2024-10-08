<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AdvanceSalaryController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\BalanceSheetController;
use App\Http\Controllers\BalanceTransferController;
use App\Http\Controllers\CommissionWithdrawController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\InvestmentWithdrawController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductStockController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalaryIncrementController;
use App\Http\Controllers\SaleCommissionController;
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
    Route::resource('customer-payments', CustomerPaymentController::class);
    Route::get('/journals', [JournalController::class, 'index'])->name('journals.index');
    Route::post('journals-store', [JournalController::class, 'store'])->name('journals.store');
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

    Route::resource('advance_salary', AdvanceSalaryController::class);
    Route::resource('sales-commissions', SaleCommissionController::class);

    Route::get('getSalary',[PayrollController::class,'getSalary'])->name('get.salary');

    // web.php
    Route::get('/get-customers', [CustomerController::class, 'getCustomers']);
    Route::resource('balance_transfers', BalanceTransferController::class);

    Route::resource('commission-withdraw',CommissionWithdrawController::class);
    Route::resource('product-stock', ProductStockController::class);

    Route::resource('investments', InvestmentController::class);
    Route::resource('investment_withdraws', InvestmentWithdrawController::class);

    Route::get('pending-sales',[SaleController::class,'pendingSales'])->name('pending-sales');
    Route::get('dispatched-sales',[SaleController::class,'dispatchedSales'])->name('dispatched-sales');
    Route::get('delivered-sales',[SaleController::class,'deliveredSales'])->name('delivered-sales');
    Route::post('/sales/{sale}/deliver', [SaleController::class, 'deliver'])->name('sales.deliver');
    Route::post('/sales/{sale}/dispatch', [SaleController::class, 'dispatch'])->name('sales.dispatch');


    Route::get('/invoice/{id}/pdf', [PdfController::class, 'generateInvoice'])->name('invoice.pdf');

    Route::get('/sale-details/{id}', [SaleController::class, 'details'])->name('sales.details');

    Route::get('cashbook',[\App\Http\Controllers\CashbookController::class,'index'])->name('cashbook.index');

    Route::get('product-summary', [\App\Http\Controllers\ReportController::class, 'productSummary'])->name('product.summary');
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity.logs');
    Route::get('/activity-log/{id}', [ActivityLogController::class, 'show'])->name('activity.log.details');
});

