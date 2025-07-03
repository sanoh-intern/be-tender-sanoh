<?php

use App\Http\Controllers\Api\V1\BusinessLicense\BusinessLicenseController;
use App\Http\Controllers\Api\V1\Dashboard\DashboardAdminController;
use App\Http\Controllers\Api\V1\File\FileController;
use App\Http\Controllers\Api\V1\Nib\NibController;
use App\Http\Controllers\Api\V1\Verification\VerificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\User\AuthController;
use App\Http\Controllers\Api\V1\User\UserController;
use App\Http\Controllers\Api\V1\User\CompanyProfileController;
use App\Http\Controllers\Api\V1\User\PersonInChargeController;
use App\Http\Controllers\Api\V1\Project\ProjectDetailController;
use App\Http\Controllers\Api\V1\Project\ProjectHeaderController;
use App\Http\Controllers\Api\V1\Dashboard\DashboardSupplierController;
use App\Http\Controllers\Api\V1\IntegrityPact\IntegrityPactController;

// Route for login
Route::post('v1/login', [AuthController::class, 'login']);

// Route for download project attachment
Route::get('v1/download/project/attachment/{id}', [ProjectHeaderController::class, 'download'])->middleware('auth:sanctum');

Route::prefix('v1/guest')->group(function () {
    Route::post('register', [UserController::class,'register']);
    Route::post('resend-password', [UserController::class,'resendPassword']);
    Route::post('reset-password', [UserController::class,'resetPasswordToken']);
    Route::post('reset-password/update', [UserController::class,'resetPasswordGuest']);
    Route::post('verification-token', [UserController::class,'verificationToken']);
});

// Route for super-admin
Route::middleware(['auth:sanctum', 'userRole:super-admin'])->prefix('v1/super-admin')->group(function () {
    // Feat Dashboard
    Route::get('dashboard/mini-profile/get', [DashboardSupplierController::class, 'miniProfile']);
    Route::get('dashboard', [DashboardAdminController::class, 'loginStats']);
    Route::get('user/monthly', [DashboardAdminController::class, 'loginPerformance']);
    Route::get('user/online', [DashboardAdminController::class, 'userOnline']);
    Route::post('user/logout', [DashboardAdminController::class, 'userRevoke']);

    // Feat User
    Route::get('user/get/{id}', [UserController::class, 'getUserById']);
    Route::get('user/list', [UserController::class, 'getListUser']);
    Route::get('user/edit/{id}', [UserController::class, 'edit']);
    Route::post('user/create', [UserController::class, 'create']);
    Route::put('user/update/{id}', [UserController::class, 'update']);
    Route::patch('user/update/status/{id}', [UserController::class, 'updateStatus']);
    Route::match(['delete', 'post'], 'user/delete/{user}',[UserController::class,'delete']);

    // Route for logout
    Route::post('logout', [AuthController::class, 'logout']);
});

// Route for purchasing
Route::middleware(['auth:sanctum', 'userRole:purchasing'])->prefix('v1/purchasing')->group(function () {
    // Feat Dashboard
    Route::get('dashboard/mini-profile/get', [DashboardSupplierController::class, 'miniProfile']);

    // Feat User
    Route::get('user/get/{id}', [UserController::class, 'getUserById']);
    Route::get('user/profile/{userId}', [UserController::class, 'getUserProfile']);

    // Feat company profile
    Route::post('company-profile/update/{companyProfile}', [CompanyProfileController::class, 'updateFile']);
    Route::patch('company-profile/update/{companyProfile}', [CompanyProfileController::class, 'update']);

    // Feat Company Data
    Route::get('company-data/get',[CompanyProfileController::class,'companyData']);

    // Feat file
    Route::post('stream/file', [FileController::class, 'streamFile']);

    // Feat Verification
    Route::get('verification/get', [VerificationController::class, 'getListVerify']);
    Route::patch('verification/approve/{verificationId}', [VerificationController::class, 'approveVerify']);

    // Feat Project Header
    Route::get('project-header/manage-offer/get/all', [ProjectHeaderController::class, 'getListAllProject']);
    Route::get('project-header/registered-offer/get/all', [ProjectHeaderController::class, 'getListAllProject']);
    Route::get('project-header/get/{id}', [ProjectHeaderController::class, 'getProjectById']);
    Route::get('project-header/registered/{id}', [ProjectHeaderController::class, 'getlistUserRegistered']);
    Route::get('project-header/list-proposal/{id}', [ProjectHeaderController::class, 'getListSupplierProjectProposal']);
    Route::post('project-header/create', [ProjectHeaderController::class, 'create']);
    Route::get('project-header/edit/{id}', [ProjectHeaderController::class, 'edit']);
    Route::put('project-header/update/{id}', [ProjectHeaderController::class, 'update']);
    Route::patch('project-header/update/regis-status/{id}', [ProjectHeaderController::class, 'updateProjectStatus']);
    Route::delete('project-header/delete/{id}', [ProjectHeaderController::class, 'delete']);

    // Project Detail
    Route::get('project-detail/list-offer/get/{id}/{userId}', [ProjectDetailController::class, 'getListProjectDetail']);

    // Route for logout
    Route::post('logout', [AuthController::class, 'logout']);
});

// Route for presdir
Route::middleware(['auth:sanctum', 'userRole:presdir'])->prefix('v1/presdir')->group(function () {
    // Feat Dashboard
    Route::get('dashboard/mini-profile/get', [DashboardSupplierController::class, 'miniProfile']);

    // Feat User
    Route::get('user/get/{id}', [UserController::class, 'getUserById']);
    Route::get('user/profile/{userId}', [UserController::class, 'getUserProfile']);

    // Feat file
    Route::post('stream/file', [FileController::class, 'streamFile']);

    // Feat Company Data
    Route::get('company-data/get',[CompanyProfileController::class,'companyData']);

    // Feat Verification
    Route::get('verification/get', [VerificationController::class, 'getListVerify']);
    Route::patch('verification/approve/{verificationId}', [VerificationController::class, 'approveVerify']);

    // Feat Project Header
    Route::get('project-header/manage-offer/get/all', [ProjectHeaderController::class, 'getListAllProject']);
    Route::get('project-header/registered-offer/get/all', [ProjectHeaderController::class, 'getListAllProject']);
    Route::get('project-header/get/{id}', [ProjectHeaderController::class, 'getProjectById']);
    Route::get('project-header/registered/{id}', [ProjectHeaderController::class, 'getlistUserRegistered']);
    Route::get('project-header/list-proposal/{id}', [ProjectHeaderController::class, 'getListSupplierProjectProposal']);
    Route::post('project-header/create', [ProjectHeaderController::class, 'create']);
    Route::get('project-header/edit/{id}', [ProjectHeaderController::class, 'edit']);
    Route::put('project-header/update/{id}', [ProjectHeaderController::class, 'update']);
    Route::patch('project-header/update/regis-status/{id}', [ProjectHeaderController::class, 'updateProjectStatus']);
    Route::delete('project-header/delete/{id}', [ProjectHeaderController::class, 'delete']);
    Route::patch('project-header/accepted', [ProjectHeaderController::class, 'winner']);
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

    // Feat file
    Route::post('stream/file', [FileController::class, 'streamFile']);

    // Feat User
    Route::get('user/get/{id}', [UserController::class, 'getUserById']);
    Route::get('user/profile', [UserController::class, 'getUserProfile']);
    Route::post('user/reset-password', [UserController::class, 'resetPasswordSupplier']);

    // Feat Verification
    Route::get('verification/status', [VerificationController::class, 'verificationStatus']);
    Route::get('verification/get', [VerificationController::class, 'getUserListVerify']);
    Route::post('verification/request', [VerificationController::class, 'verifyRequest']);

    // Feat company profile
    Route::post('company-profile/update', [CompanyProfileController::class,'updateFile']);
    Route::patch('company-profile/update', [CompanyProfileController::class, 'update']);

    // Feat Person In Charge
    Route::post('pic/create', [PersonInChargeController::class,'createPic']);
    Route::patch('pic/update/{personInCharge}', [PersonInChargeController::class,'update']);
    Route::delete('pic/delete/{personInCharge}', [PersonInChargeController::class,'destroy']);

    // Feat Integrity Pact
    Route::post('intergrity-pact/create', [IntegrityPactController::class,'createIntegrityPact']);
    Route::post('intergrity-pact/update/{integrityPact}', [IntegrityPactController::class,'update']);
    Route::delete('intergrity-pact/delete/{integrityPact}', [IntegrityPactController::class,'destroy']);

    // Feat NIB
    Route::post('nib/create', [NibController::class,'createNib']);
    Route::post('nib/update/{nib}', [NibController::class,'update']);
    Route::delete('nib/delete/{nib}', [NibController::class,'destroy']);

    // Feat Business License
    Route::post('business-license/create', [BusinessLicenseController::class,'createBusinessLicense']);
    Route::post('business-license/update/{businessLicense}', [BusinessLicenseController::class,'update']);
    Route::delete('business-license/delete/{businessLicense}', [BusinessLicenseController::class,'destroy']);

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
