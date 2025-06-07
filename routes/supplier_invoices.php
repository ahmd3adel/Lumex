<?php

use App\Http\Controllers\SupplierInvoiceController;
use Illuminate\Support\Facades\Route;



    Route::resource('/supplier_invoices', SupplierInvoiceController::class);
//    Route::get('/stores/trashed' , [StoreController::class , 'trashed'])->name('stores.trashed');
    Route::put('/stores/restore/{id}' , [SupplierInvoiceController::class , 'restore'])->name('stores.restore');
    Route::DELETE('/stores/force_delete/{id}' , [SupplierInvoiceController::class , 'forceDelete'])->name('stores.forceDelete');
    Route::get('office_invoices' , [SupplierInvoiceController::class , 'office_invoices'])->name('office.invoices');
?>
