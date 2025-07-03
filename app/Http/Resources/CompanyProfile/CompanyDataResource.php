<?php

namespace App\Http\Resources\CompanyProfile;

use Illuminate\Http\Request;
use App\Trait\CheckVerificationStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyDataResource extends JsonResource
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
            'id' => $this->id,
            'user_id' => $this->user_id,
            'supplier_name' => $this->company_name,
            'bp_code' => $this->bp_code,
            'verification_status' => $this->getStatusVerification($this->user_id),
            'verified_at' => $this->profile_verified_at
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
