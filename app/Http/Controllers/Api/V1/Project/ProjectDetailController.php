<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectDetailCreateRequest;
use App\Http\Requests\Project\ProjectDetailReviewRequest;
use App\Http\Resources\Project\ProjectListProjectDetail;
use App\Models\ProjectDetail;
use App\Models\ProjectHeader;
use App\Models\User;
use App\Trait\ResponseApi;
use App\Trait\StoreFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     *
     * @param  \Illuminate\Http\Request  $request
     *                                             $request must include params userId & id (project_header_id) only for admin
     *                                             $request with params id only for user
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
        dd($data);

        return $this->returnResponseApi(
            true,
            'Get Project Detail Successful',
            ProjectListProjectDetail::collection($data),
            200,
            ['final_view_at' => ProjectHeader::where('id', $id)->value('final_view_at')]
        );
    }

    /**
     * Create new negotiation record
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function create(ProjectDetailCreateRequest $request)
    {
        $request->validated();

        $user = $request->supplier_id ?? Auth::user()->id;

        $checkDeclined = ProjectDetail::where('supplier_id', $user)
            ->where('project_header_id', $request->project_header_id)
            ->where('proposal_status', 'Declined')
            ->exists();
        if ($checkDeclined == true) {
            return $this->returnResponseApi(false, 'Your Last Proposal Has Been Declined', '', 403);
        }

        if ($request->hasFile('proposal_attach')) {
            $filePath = $this->saveFile($request->file('proposal_attach'), 'Negotiation', 'Documents', 'Project_Detail_Negotitation', 'local');
        } else {
            $filePath = null;
        }

        $checkStatusFinal = ProjectDetail::where('project_header_id', $request->project_header_id)
            ->where('supplier_id', $user)
            ->whereNotNull('proposal_status')
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
            'proposal_status' => $request->proposal_status == 'true' ? 'Final' : null ?? null,
            'proposal_revision_no' => $countSubmittedProposal,
        ]);

        return $this->returnResponseApi(true, 'Add Negotiation Successful', $projectDetail, 200);
    }

    public function review(int $id, ProjectDetailReviewRequest $request)
    {
        $request->validated();

        $getProjectDetail = ProjectDetail::where('id', $id)->first();
        if (!$getProjectDetail) {
            return $this->returnResponseApi(false, 'Project Detail Negotiation Not Found', '', 404);
        }
        $getProjectDetail->update([
            'proposal_comment' => $request->proposal_comment,
            'reviewed_by' => Auth::user()->id,
            'reviewed_at' => Carbon::now(),
        ]);

        return $this->returnResponseApi(true, 'Project Detail Negotiation Review Successful', $getProjectDetail, 200);
    }

    /**
     * Update status proposal to Accepted
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function statusAccepted(int $id)
    {
        $update = ProjectDetail::where('id', $id)->update(['proposal_status' => 'Accepted']);
        if (!$update) {
            return $this->returnResponseApi(false, 'Proposal Not Found.', '', 404);
        }

        return $this->returnResponseApi(true, 'Update Proposal Status Successful', '', 200);
    }

    /**
     * Update status proposal to Accepted
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function statusDeclined(int $id)
    {
        $update = ProjectDetail::where('id', $id)->update(['proposal_status' => 'Declined']);
        if (!$update) {
            return $this->returnResponseApi(false, 'Proposal Not Found.', '', 404);
        }

        return $this->returnResponseApi(true, 'Update Proposal Status Successful', '', 200);
    }
}
