<?php

namespace App\Trait;

use App\Trait\ResponseApi;
use App\Models\CompanyProfile;
use App\Models\VerifyNotification;

trait CheckVerificationStatus
{
    /**
     * -------TRAIT---------
     * Mandatory:
     * 1. ResponseApi = Response api should use ResponseApi trait template
     */
    use ResponseApi;

    public function checkVerificationStatus(int $userId){
        $companyProfile = CompanyProfile::where('user_id', $userId)->first();
        if (!$companyProfile) {
            return $this->returnResponseApi(false, 'Company Profile Not Found', null, 404);
        }

        // Get Data Verification
        $verify = VerifyNotification::where('user_id', $userId)->latest()->first();

        // Check null
        $arrayData = $companyProfile->toArray();
        unset(
            $arrayData['company_description'],
            $arrayData['company_url'],
            $arrayData['company_photo'],
            $arrayData['sub_business_field'],
            $arrayData['adr_line_2'],
            $arrayData['adr_line_3'],
            $arrayData['adr_line_4'],
            $arrayData['company_phone_2'],
            $arrayData['company_fax_2'],
            $arrayData['bp_code'],
            $arrayData['created_at'],
            $arrayData['updated_at'],
            $arrayData['profile_verified_by'],
            $arrayData['profile_verified_at']
        );
        $checkNull = in_array(null, $arrayData, true);

        // Check Verification Condition
        $data = [];
        if ($checkNull == true) { // Complete profile - Please complete your company profile before requesting verification.
            $data['status_verification'] = 'complete_profile';
            $data['updated_at'] = $companyProfile->updated_at;
        } elseif (empty($verify)) { // If there is no verification - Please request verification.
            $data['status_verification'] = 'not_verified';
            $data['updated_at'] = $companyProfile->updated_at;
        } elseif (
                // updated profile - Your profile has been updated. Please request verification
            ($companyProfile->profile_verified_by == null && $companyProfile->profile_verified_at == null) &&
            ($companyProfile->created_at != $companyProfile->updated_at) &&
            ($verify->status == 'Accepted' || empty($verify))
        ) {
            $data['status_verification'] = 'profile_updated';
            $data['updated_at'] = $companyProfile->updated_at;
        } elseif (
                // updated profile - Your Last verifiy Request is Declined. Please request re-verification.
            ($companyProfile->profile_verified_by == null && $companyProfile->profile_verified_at == null) &&
            ($companyProfile->created_at != $companyProfile->updated_at) &&
            ($verify->status == 'Declined' || empty($verify))
        ) {
            $data['status_verification'] = 'profile_updated';
            $data['updated_at'] = $companyProfile->updated_at;
        } elseif (
                // Not Verified 1 (firstime request verify) - Your profile has not been verified yet. Please request verification.
            ($companyProfile->profile_verified_by == null && $companyProfile->profile_verified_at == null) &&
            empty($verify)
        ) {
            $data['status_verification'] = 'not_verified';
            $data['updated_at'] = $companyProfile->updated_at;
        } elseif (
                // Not Verified 2 (user update) - Your profile has not been verified yet. Please request verification.
            (($companyProfile->profile_verified_by == null && $companyProfile->profile_verified_at == null) && $verify->status != 'Process') &&
            ($companyProfile->created_at != $companyProfile->updated_at)
        ) {
            $data['status_verification'] = 'not_verified';
            $data['updated_at'] = $companyProfile->updated_at;
        } elseif (
                // Verify on process - Under verification, please wait.
            ($companyProfile->profile_verified_by == null && $companyProfile->profile_verified_at == null) &&
            $verify->status == 'Process' ||
            ($companyProfile->profile_verified_by != null && $companyProfile->profile_verified_at != null) &&
            $verify->status == 'Process'
        ) {
            $data['status_verification'] = 'under_verification';
            $data['updated_at'] = $companyProfile->updated_at;
        } elseif (
                // Verified - Your company profile is verified
            ($companyProfile->profile_verified_by != null && $companyProfile->profile_verified_at != null) &&
            $verify->status == 'Accepted'
        ) {
            $data['status_verification'] = 'verified';
            $data['updated_at'] = $companyProfile->updated_at;
        } else {
            return $this->returnResponseApi(true, 'Verification Status Not Found', null, 404);
        }

        return $data;
    }
}
