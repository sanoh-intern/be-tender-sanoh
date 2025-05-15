<?php

namespace App\Http\Controllers\Api\V1\BusinessLicense;

use App\Trait\StoreFile;
use App\Trait\ResponseApi;
use Illuminate\Http\Request;
use App\Models\BusinessLicense;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BusinessLicense\BusinessLicenseUpdateRequest;
use App\Http\Requests\BusinessLicense\BusincessLicenseCreateRequest;

class BusinessLicenseController extends Controller
{
    /**
     * -------TRAIT---------
     * Mandatory:
     * 1. ResponseApi = Response api should use ResponseApi trait template
     * 2. StoreFile = Save file to server storage
     */
    use ResponseApi, StoreFile;

    /**
     * Store a newly created resource in storage.
     */
    public function createBusinessLicense(BusincessLicenseCreateRequest $request)
    {
        $request->validated();

        DB::transaction(function () use ($request) {
            if ($request->hasFile('business_license_file')) {
                $filePath = $this->saveFile($request->file('business_license_file'), 'business_license', 'Documents', 'business_license', 'local');
            } else {
                $filePath = null;
            }

            BusinessLicense::create([
                'user_id' => Auth::user()->id,
                'business_license_number' => $request->business_license_number,
                'business_license_file' => $filePath,
                'business_type' => $request->business_type,
                'qualification' => $request->qualification,
                'sub_classification' => $request->sub_classification,
                'issuing_agency' => $request->issuing_agency,
                'issuing_date' => $request->issuing_date,
                'expiry_date' => $request->expiry_date,
            ]);
        });

        return $this->returnResponseApi(true, 'Create Business License Success', null, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BusinessLicenseUpdateRequest $request, BusinessLicense $businessLicense)
    {
        $request->validated();
        if ($businessLicense->user_id == Auth::user()->id) {
            if ($request->hasFile('business_license_file')) {
                $filePath = $this->saveFile($request->file('business_license_file'), 'business_license', 'Documents', 'business_license', 'local');
                $oldFile = $this->deleteFile($businessLicense->business_license_file, 'local');
                if ($oldFile == false) {
                    return $this->returnResponseApi(false, 'Old File Not Found', '', 404);
                }
            }

            $businessLicense->update([
                'business_license_number' => $request->business_license_number ?? $businessLicense->business_license_number,
                'business_license_file' => $filePath ?? $businessLicense->business_license_file,
                'business_type' => $request->business_type ?? $businessLicense->business_type,
                'qualification' => $request->qualification ?? $businessLicense->qualification,
                'sub_classification' => $request->sub_classification ?? $businessLicense->sub_classification,
                'issuing_agency' => $request->issuing_agency ?? $businessLicense->issuing_agency,
                'issuing_date' => $request->issuing_date ?? $businessLicense->issuing_date,
                'expiry_date' => $request->expiry_date ?? $businessLicense->expiry_date,
            ]);
        } else {
            return $this->returnResponseApi(true, 'You are not authorized to access this resource.', null, 403);
        }

        return $this->returnResponseApi(true, 'Update Business License Data Successful', null, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BusinessLicense $businessLicense)
    {
        if ($businessLicense->user_id == Auth::user()->id) {
            $this->deleteFile($businessLicense->business_license_file);
            $businessLicense->delete();
        } else {
            return $this->returnResponseApi(true, 'You are not authorized to access this resource.', null, 403);
        }

        return $this->returnResponseApi(true, 'Delete Business License Data Successful', null, 200);

    }
}
