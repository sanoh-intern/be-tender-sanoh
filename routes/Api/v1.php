<?php

use App\Http\Controllers\Api\V1\Project\ProjectHeaderController;
use App\Http\Controllers\Api\V1\User\AuthController;
use App\Http\Controllers\Api\V1\User\UserController;
use Illuminate\Support\Facades\Route;

// Route for login
Route::post('/login', [AuthController::class, 'login']);

// Route for creating a user
Route::post('user/create', [UserController::class, 'create']);

// Route for supplier (will allow users with either "Supplier" or "Admin-Supplier" role)
Route::middleware(['auth:sanctum', 'userRole:Supplier'])->prefix('v1/supplier')->group(function () {
    Route::get('user', function () {
        return 'tes 1';
    })->name('user');

    // Route for logout
    Route::post('logout', [AuthController::class, 'logout']);
});

// Route for admin-purchasing (only users with "Admin-Purchasing" role can access)
Route::middleware(['auth:sanctum', 'userRole:Admin-Purchasing'])->prefix('v1/admin-purchasing')->group(function () {
    // Feat User
    Route::get('user/get/{id}', [UserController::class, 'get']);
    Route::post('user/create', [UserController::class, 'create']);

    // Feat Project Header
    Route::post('project-header/create', [ProjectHeaderController::class, 'create']);
    Route::put('project-header/update/{id}',[ProjectHeaderController::class, 'update']);
    Route::patch('project-header/update/regis-status/{id}',[ProjectHeaderController::class, 'updateProjectStatus']);
    Route::delete('project-header/delete/{id}',[ProjectHeaderController::class,'delete']);

    // Route for logout
    Route::post('logout', [AuthController::class, 'logout']);
});
