<?php

namespace App\Http\Resources;

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
            'company_name' => $this->companyProfile->company_name ?? null,
            'email' => $this->email ?? null,
            'role' => $this->roleTag->role_tag ?? null,
        ];
    }
}
