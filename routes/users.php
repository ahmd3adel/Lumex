<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['checkStatus'])->group(function () {
    Route::get('/' , function (){
        return view('admins.dashboard');
    });
    Route::get('/users/trashed' , [UserController::class , 'trashed'])->name('users.trashed');
    Route::put('/users/restore/{id}' , [UserController::class , 'restore'])->name('users.restore');
    Route::DELETE('/users/force_delete/{id}' , [UserController::class , 'forceDelete'])->name('users.forceDelete');
    Route::resource('/users', UserController::class);
    Route::post('/users/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    Route::get('users/store/{id}', [UserController::class, 'getStore'])->name('store.get');
    Route::resource('/profiles', ProfileController::class);
});
?>
