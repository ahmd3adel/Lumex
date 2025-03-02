<?php

use App\Http\Controllers\ReceiptVoucherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


//    Route::get('/receipts/users/{id}' , [ReceiptVoucherController::class, 'users'])->name('receipts.users');
    Route::resource('/receipts', ReceiptVoucherController::class);
    Route::get('/receipts/trashed' , [ReceiptVoucherController::class , 'trashed'])->name('receipts.trashed');
    Route::put('/receipts/rereceipt/{id}' , [ReceiptVoucherController::class , 'rereceipt'])->name('receipts.rereceipt');
    Route::DELETE('/receipts/force_delete/{id}' , [ReceiptVoucherController::class , 'forceDelete'])->name('receipts.forceDelete');
?>
