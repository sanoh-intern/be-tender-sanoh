<?php

namespace App\Http\Resources\Project;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectHeaderEditResource extends JsonResource
{
    protected $data;
    protected $invitedEmail;

    public function __construct($data, $invitedEmail) {
        $this->data = $data;
        $this->invitedEmail = $invitedEmail;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->data->id,
            'project_name' => $this->data->project_name ?? null,
            'registration_due_at' => Carbon::parse($this->data->registration_due_at)->format('Y-m-d') ?? null,
            'project_description' => $this->data->project_description ?? null,
            'project_attach' => $this->data->project_attach ? asset('storage/' . $this->data->project_attach) : null,
            'project_type' => $this->data->project_type ?? null,
            'emails' => $this->invitedEmail,
        ];
    }
}
