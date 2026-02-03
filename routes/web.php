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
    Route::post('quotations', [QuotationController::class, 'store'])->name('quotations.store');
    Route::get('quotations/create', [QuotationController::class, 'create'])->name('quotations.create');
    
    Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::post('invoices/preview', [InvoiceController::class, 'preview'])->name('invoices.preview');
    Route::get('invoices/export/pdf', [InvoiceController::class, 'exportPdf'])->name('invoices.export.pdf');
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
    
    Route::get('payments-received', [PaymentsReceivedController::class, 'index'])->name('payments.index');
    Route::get('payments-received/create', [PaymentsReceivedController::class, 'create'])->name('payments.create');
    Route::post('payments-received', [PaymentsReceivedController::class, 'store'])->name('payments.store');
    Route::get('payments-received/{payment}', [PaymentsReceivedController::class, 'show'])->name('payments.show');
    Route::get('payments-received/{payment}/edit', [PaymentsReceivedController::class, 'edit'])->name('payments.edit');
    Route::put('payments-received/{payment}', [PaymentsReceivedController::class, 'update'])->name('payments.update');
    Route::get('sales-orders', [SalesOrderController::class, 'index'])->name('orders.index');
    
    
});
