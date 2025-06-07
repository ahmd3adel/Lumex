<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\stripeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::resource('stripe', stripeController::class);
Route::post('stripe/pay', [stripeController::class, 'pay'])->name('stripe.pay');
Route::post('payment.success', [stripeController::class, 'success'])->name('payment.success');
Route::post('payment.cancel', [stripeController::class, 'cancel'])->name('payment.cancel');


?>
