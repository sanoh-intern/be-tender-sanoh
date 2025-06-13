<?php

namespace App\Http\Resources\CompanyProfile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'bp_code' => $this->bp_code ?? null,
            'company_name' => $this->company_name ?? null,
            'company_description' => $this->company_description ?? null,
            'company_photo' => $this->company_photo ?? null,
            'company_url' => $this->company_url ?? null,
            'business_field' => $this->business_field ?? null,
            'sub_business_field' => $this->sub_business_field ?? null,
            'product' => $this->product ?? null,
            'tax_id' => $this->tax_id ?? null,
            'tax_id_file' => $this->tax_id_file ?? null,
            'adr_line_1' => $this->adr_line_1 ?? null,
            'adr_line_2' => $this->adr_line_2 ?? null,
            'adr_line_3' => $this->adr_line_3 ?? null,
            'adr_line_4' => $this->adr_line_4 ?? null,
            'province' => $this->province ?? null,
            'city' => $this->city ?? null,
            'postal_code' => $this->postal_code ?? null,
            'company_status' => $this->company_status ?? null,
            'company_phone_1' => $this->company_phone_1 ?? null,
            'company_phone_2' => $this->company_phone_2 ?? null,
            'company_fax_1' => $this->company_fax_1 ?? null,
            'company_fax_2' => $this->company_fax_2 ?? null,
            'skpp_file' => $this->skpp_file ?? null
        ];
    }
}
