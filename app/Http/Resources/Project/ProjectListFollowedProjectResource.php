<?php

namespace App\Http\Resources\Project;

use App\Models\ProjectDetail;
use App\Trait\ProposalStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProjectListFollowedProjectResource extends JsonResource
{
    /**
     * -------TRAIT---------
     * Mandatory:
     * 1. ProposalStatus = check the process of proposal
     */
    use ProposalStatus;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userId = Auth::user()->id;

        return [
            'id' => (string) $this->id,
            'project_name' => $this->project_name ?? null,
            'project_type' => $this->project_type ?? null,
            'project_status' => $this->project_status ?? null,
            'register_date' => $this->userJoin->first()->pivot->created_at
                ? Carbon::parse($this->userJoin->first()->pivot->created_at)
                    ->timezone('Asia/Jakarta')
                    ->format('Y-m-d H:i')
                : null,
            'proposal_last_update' => $this->latestUpdateProposal() ?? null,
            'proposal_last_amount' => $this->projectDetail->value('proposal_total_amount') ?? null,
            'proposal_revision_no' => $this->projectDetail->value('proposal_revision_no') == null ? '-' : ($this->projectDetail->value('proposal_revision_no') ?? '0'),
            'proposal_status' => $this->checkStatusProposal($userId, $this->id),
            // 'proposal_comment' => $this->projectDetail->value('proposal_comment') ?? null,
            'project_winner' => $this->project_winner ?? null,
            'is_final' => in_array($this->projectDetail->value('proposal_status'), ['Final', 'Accepted', 'Declined']) ? true : false,
        ];
    }

    private function latestUpdateProposal()
    {
        $projectId = $this->id;
        $projectDetailId = $this->projectDetail->value('id');

        $getLatestUpdate = ProjectDetail::where('id', $projectDetailId)->where('project_header_id', $projectId)->select('created_at')->latest('created_at')->first();

        if (! $getLatestUpdate) {
            return null;
        } else {
            $data = Carbon::parse($getLatestUpdate->created_at)
                ->timezone('Asia/Jakarta')
                ->format('Y-m-d H:i');
        }

        return $data;
    }
}
