<?php

use App\Http\Controllers\PositionController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureTokenIsValid;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/users', [UserController::class, 'store'])
        ->middleware(EnsureTokenIsValid::class);

    Route::get('/users', [UserController::class, 'index']);
    
    Route::get('/users/{id}', [UserController::class, 'show']);
    
    Route::get('/positions', [PositionController::class, 'index']);
    
    Route::get('/token', TokenController::class);
});
