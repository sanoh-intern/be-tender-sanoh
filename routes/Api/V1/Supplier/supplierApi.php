<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\User\AuthController;

// Route login
Route::post('/login', [AuthController::class, 'login']);

// Route supplier
Route::middleware(['auth:sanctum', 'second'])->prefix('v1/supplier')->group(function () {
    Route::get('user', 'UserController@index')->name('user');

    //Route logout
    Route::post('logout', [AuthController::class, 'logout']);
});

