<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectHeaderCreateRequest;
use App\Http\Requests\Project\ProjectHeaderUpdateRequest;
use App\Http\Requests\Project\ProjectHeaderWinnerRequest;
use App\Http\Resources\Project\ProjectHeaderEditResource;
use App\Http\Resources\Project\ProjectHeaderListRegisteredResource;
use App\Http\Resources\Project\ProjectHeaderResource;
use App\Http\Resources\Project\ProjectListAllProjectResource;
use App\Http\Resources\Project\ProjectListFollowedProjectResource;
use App\Http\Resources\Project\ProjectListInvitedProjectResource;
use App\Http\Resources\Project\ProjectListPublicProjectResource;
use App\Http\Resources\Project\ProjectListSupplierHeaderProposal;
use App\Models\ProjectDetail;
use App\Models\ProjectHeader;
use App\Models\ProjectInvitation;
use App\Models\User;
use App\Trait\ResponseApi;
use App\Trait\StoreFile;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProjectHeaderController extends Controller
{
    /**
     * -------TRAIT---------
     * Mandatory:
     * 1. ResponseApi = Response api should use ResponseApi trait template
     * 2. StoreFile = Save file to server storage
     */
    use ResponseApi, StoreFile;

    /**
     * get list all project
     * note:
     * 1. only for admin
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getListAllProject()
    {
        $data = ProjectHeader::with('userJoin')->get();
        if ($data->isEmpty()) {
            return $this->returnResponseApi(true, 'There is No Project Created', '', 200);
        }

        return $this->returnResponseApi(true, 'Get All Project Header Successful', ProjectListAllProjectResource::collection($data), 200);
    }

    /**
     * Get list public project
     * note:
     * 1. Must return only project Public
     * 2. Must return only registration status Open
     * 3. Must return only project status Ongoing
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getListPublicProject()
    {
        $data = ProjectHeader::select('id', 'created_at', 'project_name', 'project_type', 'project_status', 'registration_due_at', 'registration_status')
            ->where('project_status', 'Ongoing')
            ->where('project_type', 'Public')
            ->where('registration_status', 'Open')
            ->get();

        if ($data->isEmpty()) {
            return $this->returnResponseApi(false, 'No Public Project Available', '', 200);
        }

        return $this->returnResponseApi(true, 'Get Public Project Successful', ProjectListPublicProjectResource::collection($data), 200);
    }

    /**
     * Get list project of invited user
     * note:
     * 1. Must return only registration status Open
     * 2. Must return only project status Ongoing
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getListInvitedProject()
    {
        $user = Auth::user()->id;

        $data = ProjectHeader::select('id', 'created_at', 'project_name', 'project_type', 'project_status', 'registration_due_at', 'registration_status')
            ->where('project_status', 'Ongoing')
            ->where('registration_status', 'Open')
            ->whereIn(
                'id',
                ProjectInvitation::where('user_id', $user)
                    ->where('invitation_status', 'Pending')
                    ->pluck('project_header_id')
            )
            ->get();

        return $this->returnResponseApi(true, 'Get Invited Project Successful', ProjectListInvitedProjectResource::collection($data), 200);
    }

    /**
     * Get project header details by id
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getProjectById(int $id)
    {
        $data = ProjectHeader::select(
            'id',
            'created_at',
            'project_name',
            'project_type',
            'project_status',
            'project_winner',
            'project_description',
            'project_attach',
            'registration_due_at',
            'registration_status',
        )
            ->where('id', $id)
            ->first();
        if (! $data) {
            return $this->returnResponseApi(false, 'Project Header Not found', '', 404);
        }

        return $this->returnResponseApi(true, 'Get Project Header Successful', new ProjectHeaderResource($data), 200);
    }

    /**
     * Get List of user Followed Project
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getListFollowedProject()
    {
        $user = Auth::user();

        $getProjectId = $user->userProject->pluck('id');

        $getProject = ProjectHeader::with([
            'userJoin' => function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orderBy('list_user_project.created_at', 'desc');
            },
            'projectDetail' => function ($query) use ($user) {
                $query->where('supplier_id', $user->id);
                $query->latest('created_at');
            },
        ])
            ->select('id', 'project_name', 'project_type', 'project_winner', 'project_status')
            ->whereIn('id', $getProjectId)
            ->get();
        if ($getProject->isEmpty()) {
            return $this->returnResponseApi(false, 'There Is No Project You Follow', '', 200);
        }

        return $this->returnResponseApi(true, 'Get Followed Project Successful', ProjectListFollowedProjectResource::collection($getProject), 200);
    }

    /**
     * Get list of supplier latest
     *
     * @param  int  $id  project header id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getListSupplierProjectProposal(int $id)
    {
        $checkRole = Auth::user()->load('roleTag')->roleTag->role_tag;

        $getProject = ProjectHeader::with('projectDetail')
            ->select('id', 'project_name', 'project_type', 'project_winner', 'final_view_at')
            ->where('id', $id)

            ->first();
        if (! $getProject) {
            return $this->returnResponseApi(false, 'There Is No Project You Follow', '', 200);
        }

        if ($checkRole == 'purchasing') {
            $getProjectDetail = $getProject->projectDetail
                ->whereNotIn('proposal_status', ['Final'])
                ->sortByDesc('created_at')
                ->unique('supplier_id')
                ->values();
            if (! $getProjectDetail) {
                return $this->returnResponseApi(false, 'Project Not Found', '', 404);
            }
        } elseif ($checkRole == 'presdir') {
            $getProjectDetail = $getProject->projectDetail
                ->sortByDesc('created_at')
                ->unique('supplier_id')
                ->values();
            if (! $getProjectDetail) {
                return $this->returnResponseApi(false, 'Project Not Found', '', 404);
            }
        }

        return $this->returnResponseApi(
            true,
            'Get Supplier Proposals Successful',
            ProjectListSupplierHeaderProposal::collection($getProjectDetail),
            200,
            ['final_view_at' => $getProject->final_view_at ?? 'Not Viewed Yet']
        );
    }

    /**
     * Get list registered supplier in project
     *
     * @param  int  $id  project header id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getlistUserRegistered(int $id)
    {
        $getProject = ProjectHeader::with('userJoin')->where('id', $id)->first();
        if (! $getProject) {
            return $this->returnResponseApi(false, 'Project Header Not Found', '', 404);
        }

        $data = $getProject->userJoin->load('companyProfile');

        return $this->returnResponseApi(true, 'Get List Registered User Successful', ProjectHeaderListRegisteredResource::collection($data), 200);
    }

    /**
     * Create New project
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function create(ProjectHeaderCreateRequest $request)
    {
        $data = DB::transaction(function () use ($request) {
            $request->validated();

            if ($request->hasFile('project_attach')) {
                $filePath = $this->saveFile($request->file('project_attach'), 'Project', 'Documents', 'Project', 'local');
            } else {
                $filePath = null;
            }

            $projectHeader = ProjectHeader::create([
                'project_name' => $request->project_name,
                'project_status' => 'Ongoing',
                'project_type' => $request->project_type,
                'project_description' => $request->project_description,
                'project_attach' => $filePath,
                'registration_status' => 'Open',
                'registration_due_at' => $request->registration_due_at,
                'created_by' => Auth::user()->id,
            ]);

            if (! empty($request->invite_email)) {
                foreach ($request->invite_email as $email) {
                    $getUserId = User::with('role')->where('email', $email)->value('id');
                    ProjectInvitation::create([
                        'user_id' => $getUserId,
                        'project_header_id' => $projectHeader->id,
                        'invitation_by' => Auth::user()->id,
                    ]);
                }
            }

            return $projectHeader;
        });

        return $this->returnResponseApi(true, 'Create Project Header Success', $data, 201);
    }

    /**
     * Get project header details
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function edit(int $id)
    {
        $data = ProjectHeader::where('id', $id)->first();
        if (! $data) {
            return $this->returnResponseApi(false, 'Project Header Not Found', '', 404);
        }

        $getInvitation = ProjectInvitation::where('project_header_id', $id)->pluck('user_id');

        $email = [];
        foreach ($getInvitation as $userId) {
            $email[] = User::where('id', $userId)->value('email');
        }

        return $this->returnResponseApi(true, 'Get Project Header Detail Successful', new ProjectHeaderEditResource($data, $email), 200);
    }

    /**
     * Update all project header data
     *
     * @param  int  $id  this is id project header
     * @param  mixed  $request
     */
    public function update(int $id, ProjectHeaderUpdateRequest $request)
    {
        $data = DB::transaction(function () use ($id, $request) {
            $request->validated();
            $getProject = ProjectHeader::where('id', $id)->first();
            if (empty($getProject)) {
                return $this->returnResponseApi(false, 'Project Not Found', '', 404);
            }

            if ($request->hasFile('project_attach')) {
                $filePath = $this->saveFile($request->file('project_attach'), 'Project', 'Documents', 'Project', 'local');
                $oldFile = $this->deleteFile($getProject->project_attach, 'local');
                if ($oldFile == false) {
                    return $this->returnResponseApi(false, 'Old File Not Found', '', 404);
                }
            }

            $getProject->update([
                'project_name' => $request->project_name ?? $getProject->project_name,
                'project_type' => $request->project_type ?? $getProject->project_type,
                'project_description' => $request->project_description ?? $getProject->project_description,
                'project_attach' => $filePath ?? $getProject->project_attach,
                'registration_status' => $request->registration_status ?? $getProject->registration_status,
                'registration_due_at' => $request->registration_due_at ?? $getProject->registration_due_at,
                'updated_by' => Auth::user()->id,
            ]);

            // bisa hapus bisa tambah kalau gaada yg baru tetap
            if (! empty($request->invite_email)) {
                $newEmail = $request->invite_email;

                $oldInviteEmail = ProjectInvitation::with('user')->where('project_header_id', $getProject->id)->get()->pluck('user.email')->toArray();

                $addEmail = array_diff($newEmail, $oldInviteEmail);
                foreach ($addEmail as $email) {
                    $getUserId = User::with('role')->where('email', $email)->value('id');

                    ProjectInvitation::create([
                        'user_id' => $getUserId,
                        'project_header_id' => $getProject->id,
                        'invitation_by' => Auth::user()->id,
                        'invitation_status' => 'Pending',
                    ]);
                }

                $deleteEmail = array_diff($oldInviteEmail, $newEmail);
                foreach ($deleteEmail as $email) {
                    $getUserId = User::with('role')->where('email', $email)->value('id');

                    $getUserId = ProjectInvitation::where('id', $getUserId)->delete();
                }
            }

            return $getProject;
        });

        return $this->returnResponseApi(true, 'Update Project Successful', $data, 200);
    }

    /**
     * Update registration status project header data
     *
     * @param  int  $id  this is id project header
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updateProjectStatus(int $id)
    {
        $getProject = ProjectHeader::where('id', $id)->first();
        if (! $getProject) {
            return $this->returnResponseApi(false, 'Project Header Not Found', '', 404);
        }

        if ($getProject->registration_status == 'Open') {
            $getProject->update([
                'registration_status' => 'Closed',
                'updated_by' => Auth::user()->id,
            ]);
        } elseif ($getProject->registration_status == 'Closed') {
            $getProject->update([
                'registration_status' => 'Open',
                'updated_by' => Auth::user()->id,
            ]);
        }

        return $this->returnResponseApi(true, 'Update project registration status successful', '', 200);
    }

    /**
     * Delete Project Header
     *
     * @param  int  $id  this is id project header
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function delete(int $id)
    {
        $getProject = ProjectHeader::where('id', $id)->first();
        if (! $getProject) {
            return $this->returnResponseApi(false, 'Project Header Not Found', '', 404);
        }
        $getProject->delete();

        return $this->returnResponseApi(true, 'Project Deleted Successful', '', 200);
    }

    /**
     * User join project
     * User can join when registration still open
     *
     * @param  int  $id  this is id project header
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function join(int $id)
    {
        $user = Auth::user()->id;

        $getProject = ProjectHeader::where('id', $id)->first();
        if (! $getProject) {
            return $this->returnResponseApi(false, 'Project Header Not Found', '', 404);
        }

        $checkDuplicate = ProjectHeader::whereHas('userJoin', function ($query) use ($id, $user) {
            $query->where('project_header_id', $id);
            $query->where('user_id', $user);
        })
            ->exists();
        if ($checkDuplicate == true) {
            return $this->returnResponseApi(false, 'User Has Been Join', '', 403);
        }

        $checkInvitation = ProjectInvitation::where('user_id', $user)->where('project_header_id', $getProject->id)->exists();
        switch ($checkInvitation) {
            case true:
                $getInvitation = ProjectInvitation::where('user_id', $user)->where('project_header_id', $getProject->id)->first();
                if (! $getInvitation) {
                    return $this->returnResponseApi(false, 'Project Invitation Not Found', '', 404);
                }

                $getInvitation->update(['invitation_status' => 'Accepted']);

                $getProject->userJoin()->attach($user);
                break;
            case false:
                $checkProjectType = $getProject->project_type;

                if ($checkProjectType == 'Private') {
                    return $this->returnResponseApi(false, 'Project is Private', '', 403);
                } elseif ($checkProjectType == 'Public' && $getProject->registration_status == 'Open') {
                    $getProject->userJoin()->attach($user);

                } elseif ($getProject->registration_status == 'Closed') {
                    return $this->returnResponseApi(false, 'Project Registration Closed', '', 404);
                } else {
                    return $this->returnResponseApi(false, 'Project Type Invalid', '', 406);
                }
                break;

            default:
                return $this->returnResponseApi(false, 'User Invitation Not Found', '', 404);
        }

        return $this->returnResponseApi(true, 'User Join Project Successful', '', 200);
    }

    /**
     * Select the user who win the project
     * Can add more the one winner in project
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function winner(ProjectHeaderWinnerRequest $request)
    {
        $userWinner = [];
        foreach ($request->project_detail_id as $id) {
            $getProjectDetail = ProjectDetail::where('id', $id)->first();
            if (! $getProjectDetail) {
                return $this->returnResponseApi(false, 'Project Detail Not Found', '', 404);
            }

            $getProject = ProjectHeader::where('id', $getProjectDetail->project_header_id)->first();
            if (! $getProject) {
                return $this->returnResponseApi(false, 'Project Header Not Found', '', 404);
            }

            $checkUser = User::with('companyProfile')->where('id', $getProjectDetail->supplier_id)->first();
            if (! $checkUser) {
                return $this->returnResponseApi(false, 'User Not Found', '', 404);
            } else {
                $getProject->userWinner()->attach($getProjectDetail->supplier_id);
            }

            $userWinner[] = $checkUser->companyProfile->company_name ?? null;
        }

        try {
            DB::transaction(function () use ($getProjectDetail, $request, $getProject, $userWinner) {
                $getProjectHeader = ProjectHeader::with('projectDetail')->find($getProjectDetail->project_header_id);
                if (! $getProjectHeader) {
                    return $this->returnResponseApi(false, 'Project Header Not Found', '', 200);
                }

                $getLatestProposal = $getProjectHeader->projectDetail
                    ->sortByDesc('created_at')
                    ->unique('supplier_id')
                    ->values()
                    ->pluck('id')
                    ->toArray();

                $proposalWinId = $request->project_detail_id;

                $getDeclineProposalId = array_diff($getLatestProposal, $proposalWinId);

                if (! empty($getDeclineProposalId)) {
                    foreach ($getDeclineProposalId as $id) {
                        ProjectDetail::where('id', $id)->update(['proposal_status' => 'Declined']);
                    }
                }

                if (! empty($proposalWinId)) {
                    foreach ($proposalWinId as $id) {
                        ProjectDetail::where('id', $id)->update(['proposal_status' => 'Accepted']);
                    }
                }

                $userWinnerToString = implode(',', $userWinner);
                $getProject->update([
                    'project_status' => 'Supplier Selected',
                    'project_winner' => $userWinnerToString,
                    'final_review_by' => Auth::user()->id,
                    'final_review_at' => Carbon::now(),
                ]);
            });
        } catch (\Throwable $th) {
            return $this->returnResponseApi(false, 'Update Status Error', '', 500);
        }

        return $this->returnResponseApi(true, 'Project Winner Successfuly Added', '', 200);
    }

    /**
     * Download Project Header Attachment
     *
     * @return mixed|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(int $id)
    {
        $file = ProjectHeader::select('project_name', 'project_attach')->where('id', $id)->first();
        if (! $file) {
            return $this->returnResponseApi(false, 'Project Header Not Found', '', 404);
        }

        try {
            $filePath = Storage::disk('local')->path($file->project_attach);
        } catch (\Throwable $th) {
            return $this->returnResponseApi(false, 'There is No File', '', 404);
        }

        $fileName = str_replace(' ', '_', Carbon::now()->format('Ymd').'_'.$file->project_name);

        return response()->download($filePath, $fileName);
    }
}
