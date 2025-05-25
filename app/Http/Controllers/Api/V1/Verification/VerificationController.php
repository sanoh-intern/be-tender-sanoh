<?php

namespace App\Http\Controllers\Api\V1\Verification;

use App\Models\BusinessLicense;
use App\Models\IntegrityPact;
use App\Models\Nib;
use Carbon\Carbon;
use Log;
use Auth;
use App\Trait\GetEmail;
use App\Trait\ResponseApi;
use Illuminate\Http\Request;
use App\Models\CompanyProfile;
use App\Models\VerifyNotification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use function PHPUnit\Framework\isEmpty;
use App\Mail\MailInternalVerificationRequest;
use App\Http\Resources\Verification\VerifcationListResource;
use App\Http\Requests\Verification\VerificationApproveRequest;
use App\Http\Resources\Verification\VerifcationListHistoryuserResource;

class VerificationController extends Controller
{
    /**
     * -------TRAIT---------
     * Mandatory:
     * 1. ResponseApi = Response api should use ResponseApi trait template
     * 2. GetEmail = Get email user
     */
    use ResponseApi, GetEmail;

    /**
     * Approve user verification request
     * if Accepted request must include "bp_code"
     * if Decline request must include "message"
     * @param \App\Http\Requests\Verification\VerificationApproveRequest $request
     * @param \App\Models\VerifyNotification $verificationId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function approveVerify(VerificationApproveRequest $request, VerifyNotification $verificationId)
    {
        $request->validated();
        $adminId = Auth::user()->id; // User must be  purchasing or presdir
        $userId = $verificationId->user_id;

        DB::transaction(function () use ($request, $verificationId, $adminId, $userId) {
            switch ($request->status) {
                case 'Accepted':
                    // Verification
                    $verificationId->update([
                        'status' => 'Accepted',
                        'verify_by' => $adminId,
                        'verify_at' => Carbon::now(),
                        'expires_at' => Carbon::now(),
                    ]);

                    // Company profile
                    $companyProfile = CompanyProfile::where('user_id', $userId)->first();
                    if (!$companyProfile) {
                        return $this->returnResponseApi(false, 'Company Profile Data Not Found', null, 404);
                    } else {
                        $companyProfile->update([
                            'bp_code' => $request->bp_code,
                            'profile_verified_by' => $adminId,
                            'profile_verified_at' => Carbon::now(),
                        ]);
                    }

                    // Nib
                    $nib = Nib::where('user_id', $userId)->first();
                    if (!$nib) {
                        return $this->returnResponseApi(false, 'NIB Data Not Found', null, 404);
                    } else {
                        $nib->update([
                            'nib_verified_by' => $adminId,
                            'nib_verified_at' => Carbon::now(),
                        ]);
                    }

                    // Business license
                    $businessLicenses = BusinessLicense::where('user_id', $userId)->get();
                    if (empty($businessLicenses)) {
                        return $this->returnResponseApi(false, 'Business License Data Not Found', null, 404);
                    } else {
                        foreach ($businessLicenses as $license) {
                            $license->update([
                                'business_license_verified_by' => $adminId,
                                'business_license_verified_at' => Carbon::now(),
                            ]);
                        }
                    }

                    // integrity pact
                    $integrityPact = IntegrityPact::where('user_id', $userId)->first();
                    if (!$integrityPact) {
                        return $this->returnResponseApi(false, 'Integrity Pact Data Not Found', null, 404);
                    } else {
                        $integrityPact->update([
                            'integrity_pact_verified_by' => $adminId,
                            'integrity_pact_verified_at' => Carbon::now(),
                        ]);
                    }
                    break;

                case 'Declined':
                    // Verification
                    $verificationId->update([
                        'status' => 'Declined',
                        'message' => $request->message,
                        'verify_by' => $adminId,
                        'verify_at' => Carbon::now(),
                        'expires_at' => Carbon::now(),
                    ]);
                    break;

                default:
                    return $this->returnResponseApi(false, 'Invalid Status Approve', null, 404);
            }
        });

        return $this->returnResponseApi(true, 'Approve Verify Data Successful', null, 200);
    }

    /**
     * Get list all request for verification
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getListVerify()
    {
        $verifyData = VerifyNotification::with('companyProfile')->where('category', 'Verification')->whereNull('expires_at')->get();
        if (empty($verifyData)) {
            return $this->returnResponseApi(true, 'There is No Verification Request', null, 404);
        }

        return $this->returnResponseApi(true, 'Request Verify Data Successful', VerifcationListResource::collection($verifyData), 201);
    }

    /**
     * Get list all user request for verification
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getListUserVerify()
    {
        $userId = Auth::user()->id;
        $data = VerifyNotification::where('user_id', $userId)->get();
        if (isEmpty($data)) {
            return $this->returnResponseApi(true, 'There is No Verification Request', null, 404);
        }

        return $this->returnResponseApi(true, 'Request Verify Data Successful', new VerifcationListHistoryuserResource($data), 201);
    }

    /**
     * User make request verification
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function verifyRequest()
    {
        $userId = Auth::user()->id;

        $companyName = CompanyProfile::where('user_id', $userId)->get('company_name');

        $checkReqDuplication = VerifyNotification::where('user_id', $userId)->where('category', 'verification')->whereNull('status')->exists();
        if ($checkReqDuplication == true) {
            return $this->returnResponseApi(true, 'User Already Requested Verification', null, 403);
        } else {
            VerifyNotification::create([
                'user_id' => $userId,
                'category' => 'Verification',
                'description' => 'User Requested To Verify Data',
            ]);
        }

        try {
            $email = $this->getEmailByRole('purchasing');
            Mail::to($email)->queue(new MailInternalVerificationRequest($companyName));
        } catch (\Throwable $th) {
            Log::warning("$th");
        }

        return $this->returnResponseApi(true, 'Request Verify Data Successful', null, 201);
    }
}
