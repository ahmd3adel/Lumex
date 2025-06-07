<?php

use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/suppliers/getMysuppliers/{store}', [SupplierController::class, 'getMysuppliers'])->name('suppliers.getMysuppliers');
Route::resource('suppliers', SupplierController::class);
Route::get('/suppliers/trashed' , [SupplierController::class , 'trashed'])->name('suppliers.trashed');
Route::put('/suppliers/restore/{id}' , [SupplierController::class , 'restore'])->name('users.restore');
Route::DELETE('/suppliers/force_delete/{id}' , [SupplierController::class , 'forceDelete'])->name('suppliers.forceDelete');
Route::post('/suppliers/toggle-status', [SupplierController::class, 'toggleStatus'])->name('suppliers.toggleStatus');
Route::get('/get-suppliers-by-store', [SupplierController::class, 'getsuppliersByStore'])->name('get.suppliers.by.store');

?>
