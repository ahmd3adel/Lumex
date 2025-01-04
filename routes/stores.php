<?php

use App\Http\Controllers\UserController;

Route::middleware(['auth'])->group(function () {
    Route::resource('/stores', \App\Http\Controllers\StoreController::class)->except(['create' , 'show']);
    Route::get('/stores/trashed' , [\App\Http\Controllers\StoreController::class , 'trashed'])->name('users.trashed');
    Route::put('/users/restore/{id}' , [\App\Http\Controllers\StoreController::class , 'restore'])->name('users.restore');
    Route::DELETE('/users/force_delete/{id}' , [\App\Http\Controllers\StoreController::class , 'forceDelete'])->name('users.forceDelete');
});
?>
