<?php

use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [UserController::class, 'logout']);

    Route::get('/user_info', [UserController::class, 'user_info']);

    Route::post('/change_password', [UserController::class, 'change_password']);

    Route::post('/send_reset_password_email', [PasswordResetController::class, 'send_reset_password_email']);

    Route::post('/reset/{token}', [PasswordResetController::class, 'reset']);
});
