<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Resources\CompanyProfile\CompanyDataResource;
use Auth;
use App\Trait\StoreFile;
use App\Trait\ResponseApi;
use App\Models\CompanyProfile;
use App\Trait\AuthorizationRole;
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
     * 3. AuthorizationRole = for checking permissible user role
     */
    use ResponseApi, StoreFile, AuthorizationRole;

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyProfileUpdateRequest $request, CompanyProfile $companyProfile)
    {
        $request->validated();
        if ($this->permissibleRole('purchasing', 'review')) {
            $userId = null;
        } else {
            $userId = Auth::user()->id;
        }


        DB::transaction(function () use ($request, $companyProfile, $userId) {
            // dd($request);
            // Check if company photo exist
            if ($request->hasFile('company_photo')) {
                $companyPhoto = $this->saveFile($request->file('company_photo'), 'Company_Photo', 'Images', 'Company_Photo', 'public');
            }

            // Check if tax_id_file exist
            if ($request->hasFile('tax_id_file')) {
                $taxIdFile = $this->saveFile($request->file('tax_id_file'), 'tax_id', 'Documents', 'tax_id');
            }

            // Check if skpp_file exist
            if ($request->hasFile('skpp_file')) {
                $skppFile = $this->saveFile($request->file('skpp_file'), 'skpp', 'Documents', 'skpp');
            }

            // Update record
            if ($userId != null) {
                $companyProfile->where('user_id', $userId)->update([
                    'bp_code' => $request->bp_code,
                    'tax_id' => $request->tax_id,
                    'tax_id_file' => $taxIdFile,
                    'company_name' => $request->company_name,
                    'company_status' => $request->company_status,
                    'company_description' => $request->company_description,
                    'company_photo' => $companyPhoto,
                    'company_url' => $request->company_url,
                    'business_field' => $request->business_field,
                    'sub_business_field' => $request->sub_business_field,
                    'product' => $request->product,
                    'adr_line_1' => $request->adr_line_1,
                    'adr_line_2' => $request->adr_line_2,
                    'adr_line_3' => $request->adr_line_3,
                    'adr_line_4' => $request->adr_line_4,
                    'province' => $request->province,
                    'city' => $request->city,
                    'postal_code' => $request->postal_code,
                    'company_phone_1' => $request->company_phone_1,
                    'company_phone_2' => $request->company_phone_2,
                    'company_fax_1' => $request->company_fax_1,
                    'company_fax_2' => $request->company_fax_2,
                    'skpp_file' => $skppFile,
                    'profile_verified_by' => null,
                    'profile_verified_at' => null,
                ]);
            } else {
                $companyProfile->update([
                    'bp_code' => $request->bp_code,
                    'tax_id' => $request->tax_id,
                    'tax_id_file' => $taxIdFile,
                    'company_name' => $request->company_name,
                    'company_status' => $request->company_status,
                    'company_description' => $request->company_description,
                    'company_photo' => $companyPhoto,
                    'company_url' => $request->company_url,
                    'business_field' => $request->business_field,
                    'sub_business_field' => $request->sub_business_field,
                    'product' => $request->product,
                    'adr_line_1' => $request->adr_line_1,
                    'adr_line_2' => $request->adr_line_2,
                    'adr_line_3' => $request->adr_line_3,
                    'adr_line_4' => $request->adr_line_4,
                    'province' => $request->province,
                    'city' => $request->city,
                    'postal_code' => $request->postal_code,
                    'company_phone_1' => $request->company_phone_1,
                    'company_phone_2' => $request->company_phone_2,
                    'company_fax_1' => $request->company_fax_1,
                    'company_fax_2' => $request->company_fax_2,
                    'skpp_file' => $skppFile,
                    'profile_verified_by' => null,
                    'profile_verified_at' => null,
                ]);
            }

        });

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
