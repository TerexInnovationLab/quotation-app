<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sales\DashboardController;
use App\Http\Controllers\Sales\QuotationController;
use App\Http\Controllers\Sales\InvoiceController;
use App\Http\Controllers\Sales\PaymentsReceivedController;
use App\Http\Controllers\Sales\SalesOrderController;

Route::redirect('/', '/sales/quotations');

Route::prefix('sales')->name('sales.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('quotations', [QuotationController::class, 'index'])->name('quotations.index');
    Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('payments-received', [PaymentsReceivedController::class, 'index'])->name('payments.index');
    Route::get('sales-orders', [SalesOrderController::class, 'index'])->name('orders.index');
});
