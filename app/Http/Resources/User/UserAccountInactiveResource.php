<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAccountInactiveResource extends JsonResource
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
            'message' => "Account is Inactive. Please Contact PT Sanoh Indonesia.",
            'error' => "Account with (email:{$request->email}) is Inactive"
        ];
    }
}
