<?php

use App\Http\Controllers\DeductionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


//    Route::get('/deductions/users/{id}' , [ReceiptVoucherController::class, 'users'])->name('deductions.users');
    Route::resource('/deductions', DeductionController::class);
    Route::get('/deductions/trashed' , [DeductionController::class , 'trashed'])->name('deductions.trashed');
    Route::put('/deductions/rereceipt/{id}' , [DeductionController::class , 'rereceipt'])->name('deductions.rereceipt');
    Route::DELETE('/deductions/force_delete/{id}' , [DeductionController::class , 'forceDelete'])->name('deductions.forceDelete');
?>
