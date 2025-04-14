<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardMiniProfileResource extends JsonResource
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
            'company_name' => $this->companyProfile->company_name ?? null,
            'tax_id' => $this->companyProfile->tax_id ?? null,
            'company_description' => $this->companyProfile->company_description ?? null,
            'business_field' => $this->companyProfile->business_field ?? null,
            'sub_business_field' => $this->companyProfile->sub_business_field ?? null,
            'profile_verified_at' => $this->companyProfile->profile_verified_at ?? null,
        ];
    }
}
