<?php

use App\Http\Controllers\SupplierInvoiceController;
use App\Http\Controllers\SupplierProductController;
use Illuminate\Support\Facades\Route;



    Route::resource('/supplier_products', SupplierProductController::class);
//    Route::get('/stores/trashed' , [StoreController::class , 'trashed'])->name('stores.trashed');
    Route::put('/stores/restore/{id}' , [SupplierInvoiceController::class , 'restore'])->name('stores.restore');
    Route::DELETE('/stores/force_delete/{id}' , [SupplierInvoiceController::class , 'forceDelete'])->name('stores.forceDelete');
    Route::get('office_invoices' , [SupplierInvoiceController::class , 'office_invoices'])->name('office.invoices');
?>
