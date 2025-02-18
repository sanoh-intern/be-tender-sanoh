<?php

namespace App\Http\Controllers\Api\V1\Project;

use Carbon\Carbon;
use App\Trait\StoreFile;
use App\Trait\ResponseApi;
use App\Models\ProjectDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
     * Create new negotiation record
     * @param \App\Http\Requests\Project\ProjectDetailCreateRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function create(ProjectDetailCreateRequest $request)
    {

        $request->validated();
        $user = $request->supplier_id ?? Auth::user()->id;

        if ($request->hasFile('proposal_attach')) {
            $filePath = $this->saveFile($request->file('proposal_attach'), 'Negotiation', 'Project_Detail_Negotitation');
        } else {
            $filePath = null;
        }

        $projectDetail = ProjectDetail::create([
            'project_header_id' => $request->project_header_id,
            'supplier_id' => $user,
            'proposal_attach' => $filePath,
            'proposal_total_amount' => $request->proposal_total_amount,
            'proposal_status' => $request->proposal_status,
        ]);

        return $this->returnResponseApi(true, 'Add Negotiation Successful', $projectDetail, 200);
    }

    public function review(int $id, ProjectDetailReviewRequest $request)
    {
        $request->validated();

        $getProjectDetail = ProjectDetail::where('id', $id)->first();
        if (!$getProjectDetail) {
            return $this->returnResponseApi(false, 'Project Detail Negotiation Not Found','', 404);
        }
        $getProjectDetail->update([
            'proposal_comment' => $request->proposal_comment,
            'reviewed_by' => Auth::user()->id,
            'reviewed_at' => Carbon::now(),
        ]);

        return $this->returnResponseApi(true, 'Project Detail Negotiation Review Successful', $getProjectDetail, 200);
    }
}
