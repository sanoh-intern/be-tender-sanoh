<?php

namespace App\Http\Resources\Project;

use App\Models\ProjectDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectListFollowedProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd($this->projectDetail);
        return [
            'id' => $this->id,
            'project_name' => $this->project_name ?? null,
            'join_date' => $this->userJoin->first()->pivot->created_at
                ? Carbon::parse($this->userJoin->first()->pivot->created_at)
                    ->timezone('Asia/Jakarta')
                    ->format('Y-m-d h:i')
                : null,
            'last_update' => $this->tes() ?? null,
            'total_amount' => $this->projectDetail->value('proposal_total_amount') ?? null,
            'proposal_revision_no' => $this->projectDetail->value('proposal_revision_no') ?? null,
            'proposal_status' => $this->projectDetail->value('proposal_status') ?? null,
            'proposal_comment' => $this->projectDetail->value('proposal_comment') ?? null,
            'project_winner' => $this->project_winner ?? null,
        ];
    }

    private function tes()
    {
        $tes1 = $this->id;
        $tes2 = $this->projectDetail->value('id');

        $tes3 = ProjectDetail::where('id', $tes2)->where('project_header_id', $tes1)->select('created_at')->latest('created_at')->first();

        if (!$tes3) {
            return null;
        }else {
            $tes4 = Carbon::parse($tes3->created_at)
                ->timezone('Asia/Jakarta')
                ->format('Y-m-d h:i');
        }

        return $tes4;
    }
}
