<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserlistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'id_tax' => $this->tax_id ?? null,
            'account_status' => $this->account_status ?? null,
            'bp_code' => $this->companyProfile->bp_code ?? null,
            'company_name' => $this->companyProfile->company_name ?? null,
            'email' => $this->email ?? null,
            'role' => $this->roleTag->role_tag ?? null,
            'profile_verified_at' => $this->companyProfile->profile_verified_at ?? null,
            'email_verified_at' => $this->email_verified_at ?? null,
            'deleted' => ($this->deleted_at) ? true : false,
        ];
    }
}
