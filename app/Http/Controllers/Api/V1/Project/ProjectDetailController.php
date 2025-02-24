<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Models\User;
use Carbon\Carbon;
use App\Trait\StoreFile;
use App\Trait\ResponseApi;
use Illuminate\Http\Request;
use App\Models\ProjectDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Project\ProjectListProjectDetail;
use App\Http\Requests\Project\ProjectDetailCreateRequest;
use App\Http\Requests\Project\ProjectDetailReviewRequest;

class ProjectDetailController extends Controller
{
    /**
     * -------TRAIT---------
     * Mandatory:
     * 1. ResponseApi = Response api should use ResponseApi trait template
     * 2. StoreFile = Save file to server storage
     */
    use ResponseApi, StoreFile;

    /**
     * Get list project detail/offer based on user
     * @param \Illuminate\Http\Request $request
     * $request must include params userId & id only for admin
     * $request with params id only for user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getListProjectDetail(Request $request)
    {
        $id = $request->id;

        $user = $request->userId ?? Auth::user()->id;

        $data = ProjectDetail::where('project_header_id', $id)
            ->where('supplier_id', $user)
            ->get();
        if ($data->isEmpty()) {
            return $this->returnResponseApi(true, 'There is no proposal submitted.', '', 200);
        }

        return $this->returnResponseApi(true, 'Get Project Detail Successful', ProjectListProjectDetail::collection($data), 200);
    }

    public function getListSupplierProjectDetail(int $id)
    {
        $user = '';
    }

    /**
     * Create new negotiation record
     * @param \App\Http\Requests\Project\ProjectDetailCreateRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function create(ProjectDetailCreateRequest $request)
    {
        $request->validated();

        $user = $request->supplier_id ?? Auth::user()->id;

        if ($request->hasFile('proposal_attach')) {
            $filePath = $this->saveFile($request->file('proposal_attach'), 'Negotiation', 'Documents', 'Project_Detail_Negotitation', 'local');
        } else {
            $filePath = null;
        }

        $checkStatusFinal = ProjectDetail::where('project_header_id', $request->project_header_id)
            ->where('supplier_id', $user)
            ->where('proposal_status', 'Final')
            ->exists();
        if ($checkStatusFinal == true) {
            return $this->returnResponseApi(false, 'You Have Send the Final Proposal', '', 403);
        }

        $countSubmittedProposal = ProjectDetail::where('project_header_id', $request->project_header_id)
            ->where('supplier_id', $user)
            ->count();

        $projectDetail = ProjectDetail::create([
            'project_header_id' => $request->project_header_id,
            'supplier_id' => $user,
            'proposal_attach' => $filePath,
            'proposal_total_amount' => $request->proposal_total_amount,
            'proposal_status' => $request->proposal_status == true ? 'Final' : null ?? null,
            'proposal_revision_no' => $countSubmittedProposal,
        ]);

        return $this->returnResponseApi(true, 'Add Negotiation Successful', $projectDetail, 200);
    }

    public function review(int $id, ProjectDetailReviewRequest $request)
    {
        $request->validated();

        $getProjectDetail = ProjectDetail::where('id', $id)->first();
        if (! $getProjectDetail) {
            return $this->returnResponseApi(false, 'Project Detail Negotiation Not Found', '', 404);
        }
        $getProjectDetail->update([
            'proposal_comment' => $request->proposal_comment,
            'reviewed_by' => Auth::user()->id,
            'reviewed_at' => Carbon::now(),
        ]);

        return $this->returnResponseApi(true, 'Project Detail Negotiation Review Successful', $getProjectDetail, 200);
    }
}
