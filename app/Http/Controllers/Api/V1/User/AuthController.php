<?php

namespace App\Http\Controllers\Api\V1\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\UserLoginRequest;
use App\Http\Resources\User\UserAuthResource;
use App\Http\Resources\User\UserLoginResource;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthController extends Controller
{
    /**
     * Authenticate user login
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function login(UserLoginRequest $request)
    {
        // Auth attempt user by email and password
        if (Auth::attempt($request->only(['email', 'password']))) {
            if (Auth::user()->account_status != 1) {
                throw new HttpResponseException(
                    response()->json([
                        'status' => false,
                        'message' => 'Account is Inactive. Please Contact PT Sanoh Indonesia.',
                        'error' => "Account with (email:{$request->email}) is Inactive",
                    ], 401)
                );
            }
        } else {
            throw new HttpResponseException(
                response()->json([
                    'status' => false,
                    'message' => 'Invalid Email or Password. Please Try Again.',
                    'error' => ' Please Fill with Valid Data. if The Email and Password Correct but Still Error, Please Contact PT Sanoh Indonesia.',
                ], 401)
            );
        }

        // Get Auth User
        $user = Auth::user();

        // Generate a token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return token response
        return (new UserLoginResource($user, $token))->response()->setStatusCode(200);
    }

    /**
     * Revoke token user logout
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Revoke token
        $request->user()->currentAccessToken()->update(['expires_at' => now()]);

        // logout success respond
        return response()->json([
            'status' => true,
            'message' => 'User successfully logged out',
        ], 200);
    }
}
