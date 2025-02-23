<?php

namespace App\Http\Resources\Project;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Trait\CheckRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectListInvitedProjectResource extends JsonResource
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
            'created_at' => Carbon::parse($this->created_at)->timezone('Asia/Jakarta')->format('Y-m-d') ?? null,
            'project_type' => $this->project_type ?? null,
            'registration_due_at' => Carbon::parse($this->registration_due_at)->format('Y-m-d') ?? null,
            'registration_status' => $this->registration_status ?? null,
            'is_regis' => $this->isRegis($user, $this->id),
        ];
    }
}
