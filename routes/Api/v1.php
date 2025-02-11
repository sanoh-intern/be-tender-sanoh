<?php

use App\Http\Controllers\Api\V1\User\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\User\AuthController;

// Route login
Route::post('/login', [AuthController::class, 'login']);

// Route supplier
Route::middleware(['auth:sanctum', 'userRole: Supplier'])->prefix('v1/supplier')->group(function () {
    Route::get('user', 'UserController@index')->name('user');

    //Route logout
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::post('user/create',[UserController::class,'create']);

// Route admin purchasing
Route::middleware(['auth:sanctum', 'userRole: Admin-Purchasing'])->prefix('v1/admin-purchasing')->group(function () {
    // Route User
    // Create

    //Route logout
    Route::post('logout', [AuthController::class, 'logout']);
});
