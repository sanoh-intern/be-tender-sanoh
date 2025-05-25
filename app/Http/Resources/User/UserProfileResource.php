<?php

namespace App\Http\Resources\User;

use App\Http\Resources\BusinessLicense\BusinessLicenseResource;
use App\Http\Resources\CompanyProfile\CompanyProfileResource;
use App\Http\Resources\IntegrityPact\IntegrityPactResource;
use App\Http\Resources\Nib\NibResource;
use App\Http\Resources\Pic\PicResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    public function __construct(
        protected $companyData,
        protected $picData,
        protected $nibData,
        protected $businessLicenseData,
        protected $integrityPactData
    ) {
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'general_data' => new CompanyProfileResource($this->companyData),
            'person_in_charge' => PicResource::collection($this->picData),
            'nib' => new NibResource($this->nibData),
            'business_licences' => BusinessLicenseResource::collection($this->businessLicenseData),
            'integrity_pact' => new IntegrityPactResource($this->integrityPactData),
        ];
    }
}
