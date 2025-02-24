<?php

namespace App\Http\Resources\Project;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Trait\ProposalStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectListProjectDetail extends JsonResource
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
        return [
            'id' => (string) $this->id,
            'proposal_submit_date' => Carbon::parse($this->created_at)->timezone('Asia/Jakarta')->format('Y-m-d') ?? null,
            'proposal_total_amount' => $this->proposal_total_amount ?? null,
            'proposal_revision_no' => $this->proposal_revision_no ?? null,
            'proposal_comment' => $this->proposal_comment ?? null,
            'proposal_status' => $this->checkStatusProposal($this->supplier_id, $this->project_header_id),
            'is_final' => $this->proposal_status ? true : false,
        ];
    }
}
