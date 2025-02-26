<?php

namespace App\Http\Resources\Project;

use App\Trait\CheckRegistration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
            'created_at' => Carbon::parse($this->created_at)->timezone('Asia/Jakarta')->format('Y-m-d') ?? null,
            'project_name' => $this->project_name ?? null,
            'project_type' => $this->project_type ?? null,
            'project_status' => $this->project_status == 'Ongoing' ? 'Open' : ($this->project_status == 'Supplier Selected' ? 'Supplier Selected' : null),
            'registration_due_at' => Carbon::parse($this->registration_due_at)->format('Y-m-d') ?? null,
            'registration_status' => $this->registration_status ?? null,
            'is_regis' => $this->isRegis($user, $this->id),
        ];
    }
}
