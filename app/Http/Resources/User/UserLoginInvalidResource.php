<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLoginInvalidResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => false,
            'message' => "Invalid Email or Password. Please Try Again.",
            'error' => " Please Fill with Valid Data. if The Email and Password Correct but Still Error, Please Contact PT Sanoh Indonesia."
        ];
    }
}
