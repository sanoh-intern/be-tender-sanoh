<?php

namespace App\Http\Resources\Project;

use App\Models\ProjectHeader;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectListAllProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_name' => $this->project_name ?? null,
            'project_type' => $this->project_type ?? null,
            'project_created_at' => Carbon::parse($this->created_at)->timezone('Asia/jakarta')->format('Y-m-d') ?? null,
            'project_registration_due_at' => $this->registration_due_at ?? null,
            'project_registration_status' => $this->registration_status ?? null,
            'project_winner' => $this->project_winner ?? null,
            'project_registered_supplier' => $this->totalRegisteredSupp() ?? null,
        ];
    }

    private function totalRegisteredSupp()
    {
        $projectId = $this->id;

        $count = ProjectHeader::where('id', $projectId)
        ->whereHas('userJoin')
        ->withCount('userJoin')
        ->value('user_join_count');

        return $count;
    }
}
