<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/clients/getMyClients/{store}', [ClientController::class, 'getMyClients'])->name('clients.getMyClients');
Route::resource('/clients', ClientController::class);
Route::get('/clients/trashed' , [ClientController::class , 'trashed'])->name('clients.trashed');
Route::put('/clients/restore/{id}' , [ClientController::class , 'restore'])->name('users.restore');
Route::DELETE('/clients/force_delete/{id}' , [ClientController::class , 'forceDelete'])->name('clients.forceDelete');
Route::post('/clients/toggle-status', [ClientController::class, 'toggleStatus'])->name('clients.toggleStatus');
Route::get('/get-clients-by-store', [ClientController::class, 'getClientsByStore'])->name('get.clients.by.store');

?>
