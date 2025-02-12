<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\ResponseApi;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * -------TRAIT---------
     * Mandatory:
     * 1. ResponseApi = Response api should use ResponseApi trait template
     */
    use ResponseApi;

    /**
     *  Create new user and attach the role
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(UserCreateRequest $request)
    {
        $request->validated();

        $user = User::create([
            'company_photo' => $request->company_photo,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'account_status' => '1',
            'remember_token' => $request->remember_token,
        ]);

        // Attach user role
        $user->role()->attach($request->role);

        $data = User::with('role')->find($user->id);

        return $this->returnResponseApi(true, 'Create User Success', new UserResource($data), 201);
    }

    /**
     * Get specific user
     *
     * @return UserResource
     */
    public function get(int $id)
    {
        $user = User::with('role')->where('id', $id)->first();

        return $this->returnResponseApi(true, 'Get Data Success', new UserResource($user), 200);
    }
}
