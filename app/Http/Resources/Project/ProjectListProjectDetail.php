<?php

namespace App\Http\Resources\Project;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectListProjectDetail extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'created_at' => Carbon::parse($this->created_at)->timezone('Asia/Jakarta')->format('Y-m-d') ?? null,
            'proposal_total_amount' => $this->proposal_total_amount ?? null,
            'proposal_revision_no' => $this->proposal_revision_no ?? null,
            'proposal_comment' => $this->proposal_comment ?? null,
            'proposal_status' => $this->proposal_status ?? null,
            'is_final' => $this->proposal_status ? true : false,
        ];
    }
}
