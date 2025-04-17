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
            'company_photo' => $this->companyProfile->company_photo ? asset('storage/'.$this->companyProfile->company_photo) : null,
            'company_name' => $this->companyProfile->company_name ?? null,
            'email' => $this->email ?? null,
            'role_id' => $this->role_id,
            'account_status' => $this->account_status ?? null,
            'profile_verified_at' => $this->companyProfile->profile_verified_at ? true : false,
            'email_verified_at' => $this->email_verified_at ?? null,
            'remember_token' => $this->remember_token ?? null,
        ];
    }
}
