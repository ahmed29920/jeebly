<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/verify', [AuthController::class, 'verifyPhone']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/reset-password/send-otp', [ResetPasswordController::class, 'resetPasswordSendOTP']);
    Route::post('/reset-password/verify-otp', [ResetPasswordController::class, 'resetPasswordVerifyOTP']);
    Route::post('/reset-password/set-new-password', [ResetPasswordController::class, 'setNewPassword']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);
        Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);


        Route::post('change-password',[AuthController::class,'changePassword']);

        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/edit-profile', [AuthController::class, 'editProfile']);
    });
});
