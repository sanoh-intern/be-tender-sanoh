<?php

namespace App\Http\Controllers\Api\V1\User;

use Auth;
use App\Trait\StoreFile;
use App\Trait\ResponseApi;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
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
        });

        return $this->returnResponseApi(true, 'Update Company Profile Success', null, 200);
    }

}
