<?php

namespace App\Http\Resources\Project;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectHeaderResource extends JsonResource
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
            'project_name' => $this->project_name ?? null,
            'project_type' => $this->project_type ?? null,
            'project_status' => $this->project_status == 'Ongoing' ? 'Open' : ($this->project_status == 'Supplier Selected' ? 'Supplier Selected' : null),
            'project_winner' => $this->project_winner ?? null,
            'project_description' => $this->project_description ?? null,
            'registration_due_at' => Carbon::parse($this->registration_due_at)->format('Y-m-d') ?? null,
            'registration_status' => $this->registration_status ?? null,
        ];
    }
}
