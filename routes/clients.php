<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    Route::resource('/clients', ClientController::class)->except(['create' , 'show']);
    Route::get('/clients/trashed' , [ClientController::class , 'trashed'])->name('clients.trashed');
    Route::put('/clients/restore/{id}' , [ClientController::class , 'restore'])->name('users.restore');
    Route::DELETE('/clients/force_delete/{id}' , [ClientController::class , 'forceDelete'])->name('clients.forceDelete');
    Route::post('/clients/toggle-status', [ClientController::class, 'toggleStatus'])->name('clients.toggleStatus');
});
?>
