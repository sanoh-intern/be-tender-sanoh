<?php

use App\Http\Controllers\Api\V1\Dashboard\DashboardSupplierController;
use App\Http\Controllers\Api\V1\Project\ProjectDetailController;
use App\Http\Controllers\Api\V1\Project\ProjectHeaderController;
use App\Http\Controllers\Api\V1\User\AuthController;
use App\Http\Controllers\Api\V1\User\UserController;
use Illuminate\Support\Facades\Route;

// Route for login
Route::post('v1/login', [AuthController::class, 'login']);

// Route for download project attachment
Route::get('v1/download/project/attachment/{id}', [ProjectHeaderController::class, 'download'])->middleware('auth:sanctum');

// Route for super-admin
Route::middleware(['auth:sanctum', 'userRole:super-admin'])->prefix('v1/super-admin')->group(function () {

    // Feat User
    Route::get('user/get/{id}', [UserController::class, 'getUserById']);
    Route::get('user/list', [UserController::class, 'getListUser']);
    Route::post('user/create', [UserController::class, 'create']);

    // Route for logout
    Route::post('logout', [AuthController::class, 'logout']);
});

// Route for purchasing
Route::middleware(['auth:sanctum', 'userRole:purchasing'])->prefix('v1/purchasing')->group(function () {
    // Feat User
    Route::get('user/get/{id}', [UserController::class, 'getUserById']);

    // Feat Project Header
    Route::get('project-header/manage-offer/get/all', [ProjectHeaderController::class, 'getListAllProject']);
    Route::get('project-header/registered-offer/get/all', [ProjectHeaderController::class, 'getListAllProject']);
    Route::get('project-header/get/{id}', [ProjectHeaderController::class, 'getProjectById']);
    Route::get('project-header/registered/{user_id}', [ProjectHeaderController::class, 'getlistUserRegistered']);
    Route::get('project-header/list-proposal/{id}', [ProjectHeaderController::class, 'getListSupplierProjectProposal']);
    Route::post('project-header/create', [ProjectHeaderController::class, 'create']);
    Route::get('project-header/edit/{id}', [ProjectHeaderController::class, 'edit']);
    Route::put('project-header/update/{id}', [ProjectHeaderController::class, 'update']);
    Route::patch('project-header/update/regis-status/{id}', [ProjectHeaderController::class, 'updateProjectStatus']);
    Route::delete('project-header/delete/{id}', [ProjectHeaderController::class, 'delete']);

    // Project Detail
    Route::get('project-detail/list-offer/get/{userId}/{id}', [ProjectDetailController::class, 'getListProjectDetail']);

    // Route for logout
    Route::post('logout', [AuthController::class, 'logout']);
});

// Route for presdir
Route::middleware(['auth:sanctum', 'userRole:presdir'])->prefix('v1/presdir')->group(function () {
    // Feat User
    Route::get('user/get/{id}', [UserController::class, 'getUserById']);

    // Feat Project Header
    Route::get('project-header/manage-offer/get/all', [ProjectHeaderController::class, 'getListAllProject']);
    Route::get('project-header/registered-offer/get/all', [ProjectHeaderController::class, 'getListAllProject']);
    Route::get('project-header/get/{id}', [ProjectHeaderController::class, 'getProjectById']);
    Route::get('project-header/list-proposal/{id}', [ProjectHeaderController::class, 'getListSupplierProjectProposal']);
    Route::post('project-header/create', [ProjectHeaderController::class, 'create']);
    Route::get('project-header/edit/{id}', [ProjectHeaderController::class, 'edit']);
    Route::put('project-header/update/{id}', [ProjectHeaderController::class, 'update']);
    Route::patch('project-header/update/regis-status/{id}', [ProjectHeaderController::class, 'updateProjectStatus']);
    Route::delete('project-header/delete/{id}', [ProjectHeaderController::class, 'delete']);
    Route::post('project-header/accepted', [ProjectHeaderController::class, 'winner']);
    Route::patch('project-header/view/{id}', [ProjectHeaderController::class, 'finalView']);

    // Project Detail
    Route::get('project-detail/list-offer/get/{id}/{userId}', [ProjectDetailController::class, 'getListProjectDetail']);
    Route::patch('project-detail/accepted/{id}', [ProjectDetailController::class, 'statusAccepted']);
    Route::patch('project-detail/declined/{id}', [ProjectDetailController::class, 'statusDeclined']);

    // Route for logout
    Route::post('logout', [AuthController::class, 'logout']);
});

// Route for review
Route::middleware(['auth:sanctum', 'userRole:review'])->prefix('v1/review')->group(function () {
    // Feat User
    Route::get('user/get/{id}', [UserController::class, 'getUserById']);

    // Feat Project Header
    Route::post('project-header/winner', [ProjectHeaderController::class, 'winner']);

    // Route for logout
    Route::post('logout', [AuthController::class, 'logout']);
});

// Route for supplier
Route::middleware(['auth:sanctum', 'userRole:supplier'])->prefix('v1/supplier')->group(function () {
    // Feat Dashboard
    Route::get('dashboard/mini-profile/get', [DashboardSupplierController::class, 'miniProfile']);

    // Feat User
    Route::get('user/get/{id}', [UserController::class, 'getUserById']);

    // Project Header
    Route::get('project-header/get/{id}', [ProjectHeaderController::class, 'getProjectById']);
    Route::get('project-header/followed/get', [ProjectHeaderController::class, 'getListFollowedProject']);
    Route::get('project-header/list-public/get', [ProjectHeaderController::class, 'getListPublicProject']);
    Route::get('project-header/list-invited/get', [ProjectHeaderController::class, 'getListInvitedProject']);
    Route::get('project-header/join/{id}', [ProjectHeaderController::class, 'join']);

    // Project Detail
    Route::get('project-detail/list-offer/get/{id}', [ProjectDetailController::class, 'getListProjectDetail']);
    Route::post('project-detail/create', [ProjectDetailController::class, 'create']);

    // Route for logout
    Route::post('logout', [AuthController::class, 'logout']);
});
