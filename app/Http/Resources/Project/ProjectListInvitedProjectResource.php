<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectListInvitedProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id ?? null,
            'project_name' => $this->project_name ?? null,
            'created_at' => $this->created_at ?? null,
            'project_type' => $this->project_type ?? null,
            'registration_due_at' => $this->registration_due_at ?? null,
            'registration_status' => $this->registration_status ?? null,
        ];
    }
}
