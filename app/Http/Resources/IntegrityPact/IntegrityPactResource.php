<?php

namespace App\Http\Resources\IntegrityPact;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IntegrityPactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'integrity_pact_id' => $this->id ?? null,
            'integrity_pact_file' => $this->integrity_pact_file ?? null,
            'integrity_pact_desc' => $this->integrity_pact_desc ?? null,
        ];
    }
}
