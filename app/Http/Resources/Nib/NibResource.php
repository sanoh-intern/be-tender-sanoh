<?php

namespace App\Http\Resources\Nib;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NibResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'nib_id' => $this->nib_id ?? null,
            'nib_number' => $this->nib_number ?? null,
            'nib_file' => $this->nib_file ?? null,
            'issuing_agency' => $this->issuing_agency ?? null,
            'issuing_date' => $this->issuing_date ?? null,
            'investment_status' => $this->investment_status ?? null,
            'kbli' => $this->kbli ?? null,
        ];
    }
}
