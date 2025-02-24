<?php

namespace App\Http\Resources\Project;

use App\Models\ProjectHeader;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ProjectDetail;
use Illuminate\Support\Facades\Auth;
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
        return [
            'id' => (string) $this->id,
            'project_name' => $this->project_name ?? null,
            'project_type' => $this->project_type ?? null,
            'register_date' => $this->userJoin->first()->pivot->created_at
                ? Carbon::parse($this->userJoin->first()->pivot->created_at)
                    ->timezone('Asia/Jakarta')
                    ->format('Y-m-d h:i')
                : null,
            'proposal_last_update' => $this->latestUpdateProposal() ?? null,
            'proposal_last_amount' => $this->projectDetail->value('proposal_total_amount') ?? null,
            'proposal_revision_no' => $this->projectDetail->value('proposal_revision_no') ?? '0',
            'proposal_status' => $this->proposalStatus(),
            // 'proposal_comment' => $this->projectDetail->value('proposal_comment') ?? null,
            'project_winner' => $this->project_winner ?? null,
            'is_final' => $this->projectDetail->value('proposal_status') == "Final" ? true : false ?? null,
        ];
    }

    private function latestUpdateProposal()
    {
        $projectId = $this->id;
        $projectDetailId = $this->projectDetail->value('id');

        $getLatestUpdate = ProjectDetail::where('id', $projectDetailId)->where('project_header_id', $projectId)->select('created_at')->latest('created_at')->first();

        if (!$getLatestUpdate) {
            return null;
        } else {
            $data = Carbon::parse($getLatestUpdate->created_at)
                ->timezone('Asia/Jakarta')
                ->format('Y-m-d h:i');
        }

        return $data;
    }

    private function proposalStatus()
    {
        $user = Auth::user()->id;
        $projectId = $this->id;

        $checkProjectDetail = ProjectDetail::where('supplier_id', $user)
            ->where('project_header_id', $projectId)
            ->exists();
        if ($checkProjectDetail == false) {
            return "Not submitted";
        } else if ($checkProjectDetail == true) {
            $checkWinner = ProjectHeader::whereHas('userWinner', function ($query) use ($user) {
                $query->where('user_id', $user);
            })->where('id', $projectId)
                ->whereNotNull('final_review_by')
                ->whereNotNull('final_review_at')
                ->exists();

            switch ($checkWinner) {
                case false:
                    $checkIsAnnounced = ProjectHeader::where('id', $projectId)
                        ->whereNotNull('final_review_by')
                        ->whereNotNull('final_review_at')
                        ->exists();
                    if ($checkIsAnnounced == false) {
                        return "On Review";
                    } elseif ($checkIsAnnounced == true) {
                        return "Declined";
                    }else {
                        return null;
                    }
                case true:
                    return "Accepted";
                default:
                    return null;
            }
        }
    }
}
