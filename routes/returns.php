<?php

use App\Http\Controllers\ReturnGoodsController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


    Route::get('/returns/users/{id}' , [ReturnGoodsController::class, 'users'])->name('stores.users');

    Route::resource('/returns', ReturnGoodsController::class);
    Route::get('/returns/trashed' , [ReturnGoodsController::class , 'trashed'])->name('stores.trashed');
    Route::put('/returns/restore/{id}' , [ReturnGoodsController::class , 'restore'])->name('stores.restore');
    Route::DELETE('/returns/force_delete/{id}' , [ReturnGoodsController::class , 'forceDelete'])->name('stores.forceDelete');

?>
