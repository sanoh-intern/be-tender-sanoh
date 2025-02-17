<?php

use App\Http\Controllers\Api\V1\Project\ProjectHeaderController;
use App\Http\Controllers\Api\V1\User\AuthController;
use App\Http\Controllers\Api\V1\User\UserController;
use Illuminate\Support\Facades\Route;

// Route for login
Route::post('/login', [AuthController::class, 'login']);

// Route for creating a user
Route::post('user/create', [UserController::class, 'create']);

// Route for supplier
Route::middleware(['auth:sanctum', 'userRole:supplier'])->prefix('v1/supplier')->group(function () {
    // Feat User
    Route::get('user/get/{id}', [UserController::class, 'get']);

    // Project Header
    Route::get('project-header/join/{id}', [ProjectHeaderController::class, 'join']);

    // Route for logout
    Route::post('logout', [AuthController::class, 'logout']);
});

// Route for admin-purchasing
Route::middleware(['auth:sanctum', 'userRole:admin-purchasing'])->prefix('v1/admin-purchasing')->group(function () {
    // Feat User
    Route::get('user/get/{id}', [UserController::class, 'get']);
    Route::post('user/create', [UserController::class, 'create']);

    // Feat Project Header
    Route::post('project-header/create', [ProjectHeaderController::class, 'create']);
    Route::put('project-header/update/{id}', [ProjectHeaderController::class, 'update']);
    Route::patch('project-header/update/regis-status/{id}', [ProjectHeaderController::class, 'updateProjectStatus']);
    Route::delete('project-header/delete/{id}', [ProjectHeaderController::class, 'delete']);

    // Route for logout
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:sanctum', 'userRole:admin-presdir'])->prefix('v1/admin-presdir')->group(function () {
    // Feat Project Header
    Route::post('project-header/winner', [ProjectHeaderController::class, 'winner']);

    // Route for logout
    Route::post('logout', [AuthController::class, 'logout']);
});
