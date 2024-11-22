<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [\App\Http\Controllers\API\RegisterController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\API\AuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\API\AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

Route::prefix('users')
    ->middleware('auth:sanctum')
    ->controller(\App\Http\Controllers\API\UserController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/authenticated', 'authenticated');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

Route::prefix('wallet')
    ->middleware('auth:sanctum')
    ->controller(\App\Http\Controllers\API\WalletController::class)
    ->group(function () {
        Route::get('/', 'show');
    });

Route::prefix('transactions')
    ->middleware('auth:sanctum')
    ->controller(\App\Http\Controllers\API\TransactionController::class)
    ->group(function () {
        Route::get('/', 'index');
    });

