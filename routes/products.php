<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
    Route::resource('/products', ProductController::class);
    Route::get('/products/trashed' , [ProductController::class , 'trashed'])->name('products.trashed');
    Route::put('/products/restore/{id}' , [ProductController::class , 'restore'])->name('products.restore');
    Route::DELETE('/products/force_delete/{id}' , [ProductController::class , 'forceDelete'])->name('products.forceDelete');
    Route::post('/products/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');
?>
