<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectHeaderCreateRequest;
use App\Http\Requests\Project\ProjectHeaderUpdateRequest;
use App\Http\Requests\Project\ProjectHeaderWinnerRequest;
use App\Models\ProjectHeader;
use App\Models\ProjectInvitation;
use App\Models\User;
use App\Trait\ResponseApi;
use App\Trait\StoreFile;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProjectHeaderController extends Controller
{
    /**
     * -------TRAIT---------
     * Mandatory:
     * 1. ResponseApi = Response api should use ResponseApi trait template
     * 2. StoreFile =
     */
    use ResponseApi, StoreFile;

    /**
     * Create New project
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function create(ProjectHeaderCreateRequest $request)
    {
        $data = DB::transaction(function () use ($request) {
            $request->validated();

            $filePath = $this->saveFile($request->file('project_attach'), 'Project', 'Project');

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

            $filePath = $this->saveFile($request->file('project_attach'), 'Project', 'Project');
            if ($request->hasFile('project_attach')) {
                // dd($getProject->project_attach);
                $oldFile = $this->deleteFile($getProject->project_attach);
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
                $oldInviteEmail = ProjectInvitation::where('id', $getProject->id)->get()->toArray();
                $check = array_diff($newEmail, $oldInviteEmail);

                foreach ($check as $email) {
                    $getUserId = User::with('role')->where('email', $email)->value('id');

                    ProjectInvitation::create([
                        'user_id' => $getUserId,
                        'project_id' => $getProject->id,
                        'invitation_by' => Auth::user()->id,
                    ]);
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
        $getProject = ProjectHeader::where('id', $id)->first();
        if (! $getProject) {
            return $this->returnResponseApi(false, 'Project Header Not Found', '', 404);
        }

        if ($getProject->project_type == 'Private') {
            return $this->returnResponseApi(false, 'Project is Private', '', 403);
        } elseif ($getProject->registration_status == 'Closed') {
            return $this->returnResponseApi(false, 'Project Registration Closed', '', 404);
        } else {
            $getProject->userJoin()->attach(Auth::user()->id);
        }

        return $this->returnResponseApi(true, 'Join Project Successful', '', 200);
    }

    /**
     * Select the user who win the project
     * Can add more the one winner in project
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function winner(ProjectHeaderWinnerRequest $request)
    {
        $getProject = ProjectHeader::where('id', $request->project_header_id)->first();
        if (! $getProject) {
            return $this->returnResponseApi(false, 'Project Header Not Found', '', 404);
        }

        $userWinner = [];
        foreach ($request->user_id as $id) {
            $checkUser = User::with('companyProfile')->where('id', $id)->first();
            if (! $checkUser) {
                return $this->returnResponseApi(false, 'User Not Found', '', 404);
            } else {
                $getProject->userWinner()->attach($id);
            }

            $userWinner[] = $checkUser->companyProfile->company_name ?? null;
        }

        $userWinnerToString = implode(',', $userWinner);
        $getProject->update([
            'project_winner' => $userWinnerToString,
            'final_review_by' => Auth::user()->id,
            'final_review_at' => Carbon::now(),
        ]);

        return $this->returnResponseApi(true, 'Project Winner Successfuly Added', '', 200);
    }
}
