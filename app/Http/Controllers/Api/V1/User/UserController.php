<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\UserEditResource;
use App\Http\Resources\UserlistResource;
use App\Models\CompanyProfile;
use App\Models\User;
use App\Trait\ResponseApi;
use App\Trait\StoreFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * -------TRAIT---------
     * Mandatory:
     * 1. ResponseApi = Response api should use ResponseApi trait template
     * 2. StoreFile = Save file to server storage
     */
    use ResponseApi, StoreFile;

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
        $data = User::with('CompanyProfile', 'roleTag')->find($id);

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
                'company_photo' => $imagePath,
                'role_id' => $request->role,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'account_status' => '1',
            ]);
            // Attach user role
            $user->role()->attach($request->role);

            CompanyProfile::create([
                'user_id' => $user->id,
                'company_name' => $request->company_name,
                'tax_id' => $request->tax_id,
            ]);

            $getData = User::with('role', 'companyProfile')->find($user->id);

            return $getData;
        });

        return $this->returnResponseApi(true, 'Create User Success', new UserResource($data), 201);
    }
}
