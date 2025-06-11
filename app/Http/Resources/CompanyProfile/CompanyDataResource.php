<?php

namespace App\Http\Resources\CompanyProfile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyDataResource extends JsonResource
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
            'user_id' => $this->user_id,
            'supplier_name' => $this->company_name,
            'bp_code' => $this->bp_code,
            'verification_status' => $this->verifyStatus(),
        ];
    }

    /**
     * Check status verification
     * @return bool|string
     */
    private function verifyStatus() {
        $verify_by = $this->profile_verified_by;

        $verify_at = $this->profile_verified_at;

        if ($verify_by == null && $verify_at == null) {
            return false;
        } elseif ($verify_by != null && $verify_at != null) {
            return true;
        } else {
            return "Status Not Found";
        }
    }
}
