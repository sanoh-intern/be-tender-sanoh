<?php

namespace App\Http\Controllers\Api\V1\User;


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
        return response()->json([
            'status' => true,
            'email' => $user->email,
            'role' => $user->role,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
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
