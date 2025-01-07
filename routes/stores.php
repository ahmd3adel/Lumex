<?php

use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function (){
    Route::resource('/stores', StoreController::class);
    Route::get('/stores/trashed' , [StoreController::class , 'trashed'])->name('stores.trashed');
    Route::put('/stores/restore/{id}' , [StoreController::class , 'restore'])->name('stores.restore');
    Route::DELETE('/stores/force_delete/{id}' , [StoreController::class , 'forceDelete'])->name('stores.forceDelete');
});


?>
