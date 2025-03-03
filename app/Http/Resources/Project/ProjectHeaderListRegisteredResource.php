<?php

namespace App\Http\Resources\Project;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectHeaderListRegisteredResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_user' => $this->id ?? null,
            'bp_code' => $this->companyProfile->bp_code ?? null,
            'company_name' => $this->companyProfile->company_name ?? null,
            'registered_at' => Carbon::parse($this->pivot->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i') ?? null,
        ];
    }
}
