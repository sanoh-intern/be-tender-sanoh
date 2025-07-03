<?php

namespace App\Http\Resources\Dashboard;

use Storage;
use Illuminate\Http\Request;
use App\Trait\CheckVerificationStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardMiniProfileResource extends JsonResource
{
    /**
     * -------TRAIT---------
     * Mandatory:
     * 1. ChckVerificationStatus = Get current verification status
     */
    use  CheckVerificationStatus;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'bp_code' => $this->bp_code ?? null,
            'email' => $this->email ?? null,
            'company_photo' => $this->companyProfile ? asset('storage/'.$this->companyProfile->company_photo) : null,
            'company_name' => $this->companyProfile->company_name ?? null,
            'tax_id' => $this->companyProfile->tax_id ?? null,
            'company_description' => $this->companyProfile->company_description ?? null,
            'business_field' => $this->companyProfile->business_field ?? null,
            'sub_business_field' => $this->companyProfile->sub_business_field ?? null,
            'profile_verified_at' => $this->companyProfile->profile_verified_at ?? null,
            'status_verification' => $this->getStatusVerification($this->id) ?? null,
        ];
    }

    /**
     * Get user varification status using trait
     * @param mixed $userId
     * @return mixed|string
     */
    private function getStatusVerification($userId) {
        $data = $this->checkVerificationStatus($userId);

        return $data['status_verification'];
    }
}
