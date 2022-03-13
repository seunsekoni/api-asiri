<?php

use App\Http\Controllers\V1\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\V1\Admin\Auth\LoginController;
use App\Http\Controllers\V1\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\V1\Admin\Auth\VerificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [LoginController::class, 'login'])->name('admin.login');

    Route::post('/forgot/password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('admin.password.sendResetLink');
    Route::post('/reset/password', [ResetPasswordController::class, 'reset'])->name('admin.password.update');
});

Route::group(['middleware' => ['auth:admin']], function () {
    Route::prefix('auth')->group(function () {
        Route::get('/email/verify', [VerificationController::class, 'verify'])->name('admin.verification.verify');
        Route::post('/email/resend-verification', [VerificationController::class, 'resend']);
        Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');
    });
});
