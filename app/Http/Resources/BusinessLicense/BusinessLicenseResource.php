<?php

namespace App\Http\Resources\BusinessLicense;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessLicenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'business_license_id' => $this->id ?? null,
            'business_license_number' => $this->business_license_number ?? null,
            'business_license_file' => $this->business_license_file ?? null,
            'business_type' => $this->business_type ?? null,
            'issuing_agency' => $this->issuing_agency ?? null,
            'issuing_date' => $this->issuing_date ?? null,
            'qualification' => $this->qualification ?? null,
            'sub_classification' => $this->sub_classification ?? null,
            'expiry_date' => $this->expiry_date ?? null,
        ];
    }
}
