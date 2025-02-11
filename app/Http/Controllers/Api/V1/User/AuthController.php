<?php

namespace App\Http\Controllers\Api\V1\User;


use App\Http\Resources\User\UserAuthResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\UserLoginRequest;
use App\Http\Resources\User\UserLoginInvalidResource;
use App\Http\Resources\User\UserAccountInactiveResource;

class AuthController extends Controller
{
    public function login(UserLoginRequest $request)
    {
        // Auth attempt user by email and password
        if (Auth::attempt($request->only(['email', 'password']))) {
            if (Auth::user()->account_status != 1) {
                return (new UserAccountInactiveResource($request))->response()->setStatusCode(401);
            }
        } else {
            return (new UserLoginInvalidResource($request))->response()->setStatusCode(401);
        }

        // Get Auth User
        $user = Auth::user();

        // Generate a token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return token response
        return (new UserAuthResource($user, $token))->response()->setStatusCode(200);
    }

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
