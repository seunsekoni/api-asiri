<?php

use App\Http\Controllers\V1\User\Auth\ForgotPasswordController;
use App\Http\Controllers\V1\User\Auth\LoginController;
use App\Http\Controllers\V1\User\Auth\RegisterController;
use App\Http\Controllers\V1\User\Auth\ResetPasswordController;
use App\Http\Controllers\V1\User\Auth\VerificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [LoginController::class, 'login'])->name('user.login');
    Route::post('/register', [RegisterController::class, 'register'])->name('user.register');

    Route::post('/forgot/password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('user.password.sendResetLink');
    Route::post('/reset/password', [ResetPasswordController::class, 'reset'])->name('user.password.update');
});

Route::group(['middleware' => ['auth:user']], function () {
    Route::prefix('auth')->group(function () {
        Route::get('/email/verify', [VerificationController::class, 'verify'])->name('user.verification.verify');
        Route::post('/email/resend-verification', [VerificationController::class, 'resend']);
        Route::post('/logout', [LoginController::class, 'logout'])->name('user.logout');
    });
});
