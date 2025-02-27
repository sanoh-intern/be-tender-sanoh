<?php

namespace App\Http\Resources\Project;

use App\Models\User;
use App\Trait\ProposalStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectListSupplierHeaderProposal extends JsonResource
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
            'id_negotiation' => $this->id,
            'id_supplier' => $this->supplier_id,
            'bp_code' => $this->getCompanyProfile()->bp_code,
            'company_name' => $this->getCompanyProfile()->company_name,
            'proposal_total_amount' => $this->proposal_total_amount,
            'proposal_revision_no' => $this->proposal_revision_no,
            'proposal_status' => $this->checkStatusProposal($this->supplier_id, $this->project_header_id),
            'proposal_created_at' => $this->created_at->format('Y-m-d'),
            'is_final' => in_array($this->proposal_status, ['Final', 'Accepted', 'Declined']) ? true : false,
        ];
    }

    private function getCompanyProfile()
    {
        $userId = $this->supplier_id;

        $data = User::with('companyProfile')
            ->where('id', $userId)
            ->first();

        return $data->companyProfile ?? null;
    }
}
