<?php

use App\Http\Controllers\AuthentificationController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------- | API Routes |-------------------------------------------------------------------------- |
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function (){
    Route::post('/auth/whoami', [AuthentificationController::class, 'whoami']);
    Route::post('/auth/logout', [AuthentificationController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function (){
    Route::post('/user/quit', [UserController::class, 'quit']);
});


Route::middleware('guest')->middleware('remove-cors')->group(function(){
    Route::get('/user', [UserController::class, 'findAll']);
    Route::get('/auth/email/sendVerificationCode', [EmailController::class, 'sendVerificationCode']);
    Route::get('/auth/email/verify', [EmailController::class, 'verifyEmail']);
    Route::post('/user/crupdate', [UserController::class, 'crupdate']);
    Route::post('/auth/login', [AuthentificationController::class, 'login']);
});
