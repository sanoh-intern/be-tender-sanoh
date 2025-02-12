<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_photo' => $this->company_photo,
            'email' => $this->email,
            'role_tag' => $this->role->pluck('role_tag'),
            'account_status' => $this->account_status,
            'profile_verified_at' => $this->profile_verified_at,
            'email_verified_at' => $this->email_verified_at,
            'remember_token' => $this->remember_token,
        ];
    }
}
