<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;



    Route::resource('/invoices', InvoiceController::class);
//    Route::get('/stores/trashed' , [StoreController::class , 'trashed'])->name('stores.trashed');
    Route::put('/stores/restore/{id}' , [InvoiceController::class , 'restore'])->name('stores.restore');
    Route::DELETE('/stores/force_delete/{id}' , [InvoiceController::class , 'forceDelete'])->name('stores.forceDelete');
    Route::get('office_invoices' , [InvoiceController::class , 'office_invoices'])->name('office.invoices');
?>
