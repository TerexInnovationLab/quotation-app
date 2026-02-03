<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sales\DashboardController;
use App\Http\Controllers\Sales\QuotationController;
use App\Http\Controllers\Sales\InvoiceController;
use App\Http\Controllers\Sales\PaymentsReceivedController;
use App\Http\Controllers\Sales\SalesOrderController;
use App\Http\Controllers\Sales\SettingsController;
use App\Http\Controllers\Sales\ClientController;
use App\Http\Controllers\Sales\ProductServiceController;

Route::redirect('/', '/sales/quotations');

Route::prefix('sales')->name('sales.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('quotations', [QuotationController::class, 'index'])->name('quotations.index');
    Route::post('quotations', [QuotationController::class, 'store'])->name('quotations.store');
    Route::get('quotations/create', [QuotationController::class, 'create'])->name('quotations.create');
    Route::get('quotations/export/pdf', [QuotationController::class, 'exportPdf'])->name('quotations.export.pdf');
    Route::get('quotations/{quotation}', [QuotationController::class, 'show'])->name('quotations.show');
    Route::get('quotations/{quotation}/edit', [QuotationController::class, 'edit'])->name('quotations.edit');
    Route::put('quotations/{quotation}', [QuotationController::class, 'update'])->name('quotations.update');
    
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
    Route::get('clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('clients/{client}', [ClientController::class, 'show'])->name('clients.show');
    Route::get('clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::get('products-services', [ProductServiceController::class, 'index'])->name('products.index');
    Route::get('products-services/create', [ProductServiceController::class, 'create'])->name('products.create');
    Route::post('products-services', [ProductServiceController::class, 'store'])->name('products.store');
    Route::get('products-services/{product}', [ProductServiceController::class, 'show'])->name('products.show');
    Route::get('products-services/{product}/edit', [ProductServiceController::class, 'edit'])->name('products.edit');
    Route::put('products-services/{product}', [ProductServiceController::class, 'update'])->name('products.update');
    Route::get('sales-orders', [SalesOrderController::class, 'index'])->name('orders.index');
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
    
    
});
