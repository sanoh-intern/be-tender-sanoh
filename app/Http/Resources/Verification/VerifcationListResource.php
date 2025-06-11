<?php

namespace App\Http\Resources\Verification;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VerifcationListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'verification_id' => $this->latestVerification->id,
            'user_id' => $this->id,
            'tax_id' => $this->companyProfile->tax_id ?? null,
            'comapany_name' => $this->companyProfile->company_name ?? null,
            'request_date' => Carbon::parse($this->latestVerification->created_at)->format('Y-m-d'),
        ];
    }
}
