<?php

namespace App\Http\Resources\Project;

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
            'id'=> (string) $this->id,
            'created_at'=> $this->created_at ?? null,
            'project_type'=> $this->project_type ?? null,
            'project_status'=> $this->project_status ?? null,
            'registration_due_at'=> $this->registration_due_at ?? null,
            'registration_status'=> $this->registration_status ?? null,
            'project_winner'=> $this->project_winner ?? null,
            'project_description'=> $this->project_description ?? null,
            'project_attach'=> $this->project_attach ? asset('storage/' . $this->project_attach) : null,
        ];
    }
}
