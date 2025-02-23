<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Request;
use App\Trait\CheckRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectListPublicProjectResource extends JsonResource
{
    /**
     * -------TRAIT---------
     * Mandatory:
     * 1. CheckRegistration = For checking if user has join the project
     */
    use CheckRegistration;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = Auth::user()->id;

        return [
            'id' => (string) $this->id ?? null,
            'project_name' => $this->project_name ?? null,
            'created_at' => $this->created_at ?? null,
            'project_type' => $this->project_type ?? null,
            'registration_due_at' => $this->registration_due_at ?? null,
            'registration_status' => $this->registration_status ?? null,
            'is_regis' => $this->isRegis($user, $this->id),
        ];
    }
}
