<?php

namespace App\Http\Controllers\Api\V1\User;

use Auth;
use App\Models\User;
use App\Trait\StoreFile;
use App\Trait\ResponseApi;
use App\Models\CompanyProfile;
use App\Models\VerifyNotification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\User\UserEditResource;
use App\Http\Requests\CompanyProfile\CompanyProfileUpdateRequest;

class CompanyProfileController extends Controller
{
    /**
     * -------TRAIT---------
     * Mandatory:
     * 1. ResponseApi = Response api should use ResponseApi trait template
     * 2. StoreFile = Save file to server storage
     */
    use ResponseApi, StoreFile;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyProfileUpdateRequest $request, CompanyProfile $companyProfile)
    {
        $request->validated();
        $userId = Auth::user()->id;

        DB::transaction(function () use ($request, $companyProfile, $userId) {
            // Check if company photo exist
            if ($request->hasFile('company_photo')) {
                $imagePath = $this->saveFile($request->file('company_photo'), 'Company_Photo', 'Images', 'Company_Photo', 'public');
                $request['company_photo'] = $imagePath;
            }
            
            // Update record
            $companyProfile->update($request->all());

            // Create notification
            VerifyNotification::create([
                'user_id' => $userId,
                'category' => 'Company Profile',
                'description' => 'Need to Verify Updated Company Profile',
            ]);
        });

        return $this->returnResponseApi(true, 'Update Company Profile Success', null, 200);
    }

}
