<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth' , 'checkStatus'])->group(function () {
    Route::get('admin/dashboard' , function (){
        return view('admins.dashboard');
    });
    Route::get('/' , function (){
        return view('admins.dashboard');
    });
    Route::get('/users/trashed' , [UserController::class , 'trashed'])->name('users.trashed');
//    Route::put('/users/restore/{$id}' , [UserController::class , 'restore'])->name('users.restore');
    Route::put('/users/restore/{id}' , [UserController::class , 'restore'])->name('users.restore');
    Route::DELETE('/users/force_delete/{id}' , [UserController::class , 'forceDelete'])->name('users.forceDelete');

    Route::resource('/users', UserController::class)->except(['create' , 'show']);
//    Route::resource('/newUsers', UserController::class)->except(['create' , 'show']);
    Route::post('/users/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');

});
?>
