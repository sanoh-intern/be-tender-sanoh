<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\UserCreateRequest;

class UserController extends Controller
{
    public function create(UserCreateRequest $request)
    {
        $request->validated();

        $user = User::create([
            'company_photo' => $request->company_photo,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'remember_token' => $request->remember_token,
        ]);
        
        // foreach ($request->role as $data) {
            // $user->role()->attach($data);
            $user->role()->attach($request->role);
        // }
    }
}
