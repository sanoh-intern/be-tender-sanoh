<?php

namespace App\Http\Controllers\Api\V1\User;

use Auth;
use App\Trait\StoreFile;
use App\Trait\ResponseApi;
use App\Models\CompanyProfile;
use App\Trait\AuthorizationRole;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyProfile\CompanyDataResource;
use App\Http\Requests\CompanyProfile\CompanyProfileUpdateRequest;
use App\Http\Requests\CompanyProfile\CompanyProfileUpdateFileRequest;

class CompanyProfileController extends Controller
{
    /**
     * -------TRAIT---------
     * Mandatory:
     * 1. ResponseApi = Response api should use ResponseApi trait template
     * 2. StoreFile = Save file to server storage
     * 3. AuthorizationRole = for checking permissible user role
     */
    use ResponseApi, StoreFile, AuthorizationRole;

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyProfileUpdateRequest $request, CompanyProfile $companyProfile)
    {
        if ($this->permissibleRole('purchasing', 'review')) {
            $userId = null;
        } else {
            $userId = Auth::user()->id;
        }

        // Update record
        if ($userId != null) {
            $companyProfile->where('user_id', $userId)->update($request->validated());
        } else {
            $companyProfile->update($request->validated());
        }

        return $this->returnResponseApi(true, 'Update Company Profile Success', null, 200);
    }

    public function updateFile(CompanyProfileUpdateFileRequest $request, CompanyProfile $companyProfile)
    {
        $request->validated();

        if ($this->permissibleRole('purchasing', 'review')) {
            $userId = null;
            // Get file path
            $getfile = $companyProfile->select('company_photo', 'tax_id_file', 'skpp_file')->first();
        } else {
            $userId = Auth::user()->id;
            // Get file path
            $getfile = $companyProfile->select('company_photo', 'tax_id_file', 'skpp_file')->where('user_id', $userId)->first();
        }


        // Check if company photo exist
        if ($request->hasFile('company_photo')) {
            // Delete old photo
            if ($getfile->company_photo != null) {
                $this->deleteFile($getfile->company_photo, 'public');
            }


            // Save new photo
            $companyPhoto = $this->saveFile($request->file('company_photo'), 'Company_Photo', 'Images', 'Company_Photo', 'public');
            if ($userId != null) {
                $companyProfile->where('user_id', $userId)->update([
                    'company_photo' => $companyPhoto,
                    'profile_verified_by' => null,
                    'profile_verified_at' => null,
                ]);
            } else {

                $companyProfile->update([
                    'company_photo' => $companyPhoto,
                    'profile_verified_by' => null,
                    'profile_verified_at' => null,
                ]);
            }
        }

        // Check if tax_id_file exist
        if ($request->hasFile('tax_id_file')) {
            // Delete old tax id file
            if ($getfile->tax_id_file) {
                $this->deleteFile($getfile->tax_id_file, 'public');
            }

            // Save new tax id
            $taxIdFile = $this->saveFile($request->file('tax_id_file'), 'tax_id', 'Documents', 'tax_id');
            if ($userId != null) {
                $companyProfile->where('user_id', $userId)->update([
                    'tax_id_file' => $taxIdFile,
                    'profile_verified_by' => null,
                    'profile_verified_at' => null,
                ]);
            } else {
                $companyProfile->update([
                    'tax_id_file' => $taxIdFile,
                    'profile_verified_by' => null,
                    'profile_verified_at' => null,
                ]);
            }
        }

        // Check if skpp_file exist
        if ($request->hasFile('skpp_file')) {
            // Delete old skpp file
            if ($getfile->skpp_file) {
                $this->deleteFile($getfile->skpp_file, 'public');
            }

            // Save new skpp file
            $skppFile = $this->saveFile($request->file('skpp_file'), 'skpp', 'Documents', 'skpp');
            if ($userId != null) {
                $companyProfile->where('user_id', $userId)->update([
                    'skpp_file' => $skppFile,
                    'profile_verified_by' => null,
                    'profile_verified_at' => null,
                ]);
            } else {
                $companyProfile->update([
                    'skpp_file' => $skppFile,
                    'profile_verified_by' => null,
                    'profile_verified_at' => null,
                ]);
            }
        }

        return $this->returnResponseApi(true, 'Update Company Profile Success', null, 200);
    }

    public function companyData()
    {
        $data = CompanyProfile::with('user')
            ->select('id', 'user_id', 'bp_code', 'company_name', 'profile_verified_by', 'profile_verified_at')
            ->orderByDesc('created_at')
            ->get();
        if (empty($data)) {
            return $this->returnResponseApi(true, 'There is No Company Data', '', 404);
        }

        return $this->returnResponseApi(true, 'Get Company Data Success', CompanyDataResource::collection($data), 200);
    }
}
