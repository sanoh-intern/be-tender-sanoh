<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Resources\User\UserProfileResource;
use Str;
use Mail;
use Carbon\Carbon;
use App\Models\Nib;
use App\Models\User;
use App\Trait\StoreFile;
use App\Trait\ResponseApi;
use App\Models\IntegrityPact;
use App\Models\CompanyProfile;
use App\Models\PersonInCharge;
use App\Models\BusinessLicense;
use App\Trait\AuthorizationRole;
use Illuminate\Support\Facades\DB;
use App\Mail\MailUserAfterRegister;
use App\Models\PasswordResetTokens;
use App\Http\Controllers\Controller;
use App\Mail\MailPasswordResetToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\User\UserResource;
use App\Http\Requests\User\UserEmailRequest;
use App\Http\Requests\User\UserTokenRequest;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserEditResource;
use App\Http\Resources\User\UserlistResource;
use App\Http\Requests\User\UserRegisterRequest;
use App\Http\Requests\User\UserUpdatePasswordRequest;

class UserController extends Controller
{
    /**
     * -------TRAIT---------
     * Mandatory:
     * 1. ResponseApi = Response api should use ResponseApi trait template
     * 2. StoreFile = Save file to server storage
     * 3. AuthorizationRole = Check user role
     */
    use ResponseApi, StoreFile, AuthorizationRole;

    /**
     * Display a listing of the resource.
     */
    public function getUserProfile($userId = null)
    {
        if ($this->permissibleRole('supplier')) {
            $userId = Auth::user()->id;
        } elseif ($this->permissibleRole('purchasing', 'presdir')) {
            $userId;
        }

        // Company profile
        $companyProfile = CompanyProfile::where('user_id', $userId)->first();
        if (!$companyProfile) {
            return $this->returnResponseApi(false, 'Company Profile Data Not Found', null, 404);
        }

        // Person In Charge
        $personInCharge = PersonInCharge::where('user_id', $userId)->get();
        if (empty($personInCharge)) {
            return $this->returnResponseApi(false, 'Person In Charge Data Not Found', null, 404);
        }

        // Nib
        $nib = Nib::where('user_id', $userId)->first();
        if (!$nib) {
            return $this->returnResponseApi(false, 'NIB Data Not Found', null, 404);
        }

        // Business license
        $businessLicenses = BusinessLicense::where('user_id', $userId)->get();
        if (empty($businessLicenses)) {
            return $this->returnResponseApi(false, 'Business License Data Not Found', null, 404);
        }

        // integrity pact
        $integrityPact = IntegrityPact::where('user_id', $userId)->first();
        if (!$integrityPact) {
            return $this->returnResponseApi(false, 'Integrity Pact Data Not Found', null, 404);
        }

        return $this->returnResponseApi(
            true,
            'Get User Profile Data Success',
            new UserProfileResource(
                $companyProfile,
                $personInCharge,
                $nib,
                $businessLicenses,
                $integrityPact
            ),
            200
        );
    }

    /**
     * Get specific user
     *
     * @return UserResource
     */
    public function getUserById(int $id)
    {
        $user = User::with('role', 'companyProfile')->where('id', $id)->first();

        return $this->returnResponseApi(true, 'Get Data Success', new UserResource($user), 200);
    }

    /**
     * Get list user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getListUser()
    {
        $data = User::with('companyProfile', 'roleTag')->orderBy('created_at', 'asc')->get();

        return $this->returnResponseApi(true, 'Get List User Success', UserlistResource::collection($data), 200);
    }

    /**
     * Get data edit
     * @return void
     */
    public function edit(int $id)
    {
        $data = User::with('companyProfile', 'roleTag')->find($id);

        return $this->returnResponseApi(true, 'Get Detail User Success', new UserEditResource($data), 200);
    }

    /**
     *  Create new user and attach the role
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(UserCreateRequest $request)
    {
        $data = DB::transaction(function () use ($request) {
            $request->validated();

            if ($request->hasFile('company_photo')) {
                $imagePath = $this->saveFile($request->file('company_photo'), 'Company_Photo', 'Images', 'Company_Photo', 'public');
            } else {
                $imagePath = null;
            }

            $user = User::create([
                'role_id' => $request->role,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            // Attach user role
            $user->role()->attach($request->role);

            CompanyProfile::create([
                'user_id' => $user->id,
                'tax_id' => $request->tax_id,
                'company_name' => $request->company_name,
                'company_photo' => $imagePath,
            ]);

            $getData = User::with('role', 'companyProfile')->find($user->id);

            return $getData;
        });

        return $this->returnResponseApi(true, 'Create User Success', new UserResource($data), 201);
    }

    /**
     * Update user data
     * @param \App\Http\Requests\User\UserUpdateRequest $request
     * @param int $id
     * @return void
     */
    public function update(UserUpdateRequest $request, int $id)
    {
        $request->validated();

        $user = User::with('roleTag')->find($id);
        if (!$user) {
            return $this->returnResponseApi(false, 'User Not Found', '', 404);
        }
        $user->update([
            'account_status' => $request->account_status ?? $user->account_status,
            'email' => $request->email ?? $user->email,
            'password' => Hash::make($request->password) ?? $user->password,
            'role_id' => $request->role ?? $user->roleTag->role_tag,
        ]);

        $profile = CompanyProfile::where('user_id', $id)->first();
        if (!$profile) {
            return $this->returnResponseApi(false, 'Company Profile Not Found', '', 404);
        }
        $profile->update([
            'tax_id' => $request->id_tax ?? $profile->tax_id,
            'company_name' => $request->company_name ?? $profile->company_name,
        ]);

        return $this->returnResponseApi(true, 'Update User Success', null, 200);
    }

    /**
     * Update status user account (active/inactive)
     * @param int $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updateStatus(int $id)
    {
        $user = User::find($id);

        switch ($user->account_status) {
            case '0':
                $user->update(['account_status' => '1']);
                break;
            case '1':
                $user->update(['account_status' => '0']);
                break;
            default:
                return $this->returnResponseApi(false, 'Account Status Unknown', '', 403);
        }

        return $this->returnResponseApi(true, 'Update Account Status Success', ['account_status' => $user->account_status], 200);
    }

    /**
     * Create new account for guest
     * @param \App\Http\Requests\User\UserRegisterRequest $request
     * @return void
     */
    public function register(UserRegisterRequest $request)
    {
        $request->validated();
        $password = Str::password(8);

        DB::transaction(function () use ($request, $password) {
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($password),
                'role_id' => 5,
            ]);

            CompanyProfile::create([
                'user_id' => $user->id,
                'tax_id' => $request->tax_id,
                'company_name' => $request->company_name,
            ]);
        });

        Mail::to($request->email)->queue(new MailUserAfterRegister($password));

        return $this->returnResponseApi(true, 'Create Account Success', null, 201);
    }

    /**
     * Reset Password *after Password reset token verified
     * @param \App\Http\Requests\User\UserUpdatePasswordRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function resetPassword(UserUpdatePasswordRequest $request)
    {
        $request->validated();

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->returnResponseApi(false, 'User Email Not Found', '', 404);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return $this->returnResponseApi(true, 'Reset Password Success', null, 200);
    }

    /**
     * Resend password mail *after Register, if user doesnt receive mail
     * @param \App\Http\Requests\User\UserEmailRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function resendPassword(UserEmailRequest $request)
    {
        $request->validated();
        $password = Str::password(8);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->returnResponseApi(false, 'User Email Not Found', '', 404);
        }
        // Update password
        $user->update([
            'password' => Hash::make($password),
        ]);

        // Send Mail
        Mail::to($request->email)->queue(new MailUserAfterRegister($password));

        return $this->returnResponseApi(true, 'Resend Password Success', null, 200);
    }

    /**
     * Send mail Password reset token
     * @param \App\Http\Requests\User\UserEmailRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function resetPasswordToken(UserEmailRequest $request)
    {
        $request->validated();
        $createToken = Str::random(6);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->returnResponseApi(false, 'User Email Not Found', '', 404);
        }

        /**
         * Check if token with the same email already exists
         * (to avoid error, when resend mail token if mail doesnt reach to user)
         */
        $duplicateToken = PasswordResetTokens::where('email', $request->email)->first();
        if ($duplicateToken) {
            $duplicateToken->delete();
        }

        PasswordResetTokens::create(
            [
                'email' => $request->email,
                'token' => $createToken,
                'created_at' => Carbon::now(),
            ]
        );

        // Send mail
        Mail::to($request->email)->queue(new MailPasswordResetToken($createToken));

        return $this->returnResponseApi(true, 'Send Password Reset Token Success', null, 200);
    }

    /**
     * Verify password reset token
     * @param \App\Http\Requests\User\UserTokenRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function verificationToken(UserTokenRequest $request)
    {
        $request->validated();

        $checkToken = PasswordResetTokens::where('token', $request->token)
            ->where('email', $request->email)
            ->where('status', 0)->first();
        if (!$checkToken) {
            return $this->returnResponseApi(true, 'Token invalid', null, 404);
        }

        // Update token status
        $checkToken->update(['status' => 1]);

        // Create cache
        Cache::put("reset-password-$checkToken->email", $checkToken->token, now()->addMinutes(5));

        return $this->returnResponseApi(true, 'Verification Token Success', null, 200);
    }
}
