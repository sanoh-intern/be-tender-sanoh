<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Requests\Project\ProjectHeaderUpdateRequest;
use App\Trait\StoreFile;
use Auth;
use App\Models\User;
use App\Trait\ResponseApi;
use Illuminate\Http\Request;
use App\Models\ProjectHeader;
use App\Models\ProjectInvitation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectHeaderCreateRequest;

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
     * @param \App\Http\Requests\Project\ProjectHeaderCreateRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function create(ProjectHeaderCreateRequest $request)
    {
        $data = DB::transaction(function () use ($request) {
            $request->validated();

            // person in charge
            $pic = Auth::user()->id;

            $filePath = $this->saveFile($request->file('project_attach'), 'Project', 'Project');

            $projectHeader = ProjectHeader::create([
                'project_name' => $request->project_name,
                'project_status' => 'Ongoing',
                'project_type' => $request->project_type,
                'project_description' => $request->project_description,
                'project_attach' => $filePath,
                'registration_status' => 'Open',
                'registration_due_at' => $request->registration_due_at,
                'created_by' => $pic,
            ]);

            if (!empty($request->invite_email)) {
                foreach ($request->invite_email as $email) {
                    $getUserId = User::with('role')->where('email', $email)->first()->value('id');

                    ProjectInvitation::create([
                        'user_id' => $getUserId,
                        'project_id' => $projectHeader->id,
                        'invitation_by' => $pic,
                    ]);
                }
            }
            return $projectHeader;
        });

        return $this->returnResponseApi(true, 'Create Project Header Success', $data, 201);
    }

    /**
     * Update all project header data
     * @param int $id this is id project header
     * @param mixed $request
     */
    public function update(int $id, ProjectHeaderUpdateRequest $request)
    {
        $data = DB::transaction(function () use($id, $request) {
            $request->validated();

            // person in charge
            $pic = Auth::user()->id;

            $getProject = ProjectHeader::find($id)->first();
            if (empty($getProject)) {
                return $this->returnResponseApi(false,'Project Not Found','',401);
            }

            if ($request->hasFile('project_attach')) {
                $oldFile = $this->deleteFile($getProject->project_attach);
                if ($oldFile == false) {
                    return $this->returnResponseApi(false,'Old File Not Found','',401);
                }
            }

            $filePath = $this->saveFile($request->file('project_attach'), 'Project', 'Project');

            ProjectHeader::update([
                'project_name' => $request->project_name ?? $getProject->project_name,
                'project_type' => $request->project_type ?? $getProject->project_type,
                'project_description' => $request->project_description ?? $getProject->project_description,
                'project_attach' => $filePath ?? $getProject->$filePath,
                'registration_status' => $request->registration_status ?? $getProject->registration_status,
                'registration_due_at' => $request->registration_due_at ?? $getProject->registration_due_at,
            ]);


            // bisa hapus bisa tambah kalau gaada yg baru tetap
            if (!empty($request->invite_email)) {
                $newEmail = $request->invite_email;
                $oldInviteEmail = ProjectInvitation::where('id', $getProject->id)->toArray();

                $check = array_diff($newEmail, $oldInviteEmail);

                dd($check);

                foreach ($request->invite_email as $email) {
                    $getUserId = User::with('role')->where('email', $email)->first()->value('id');

                    ProjectInvitation::create([
                        'user_id' => $getUserId,
                        'project_id' => $getProject->id,
                        'invitation_by' => $pic,
                    ]);
                }
            }

            return $getProject;
        });

        return $this->returnResponseApi(true,'Update Project Successful',$data,200);
    }

    /**
     * Update registration status project header data
     * @param int $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updateProjectStatus(int $id)
    {
        $getProject = ProjectHeader::where('id', $id)->first();
        if (!$getProject) {
            return $this->returnResponseApi(false,'Project Header Not Found','',404);
        }

        if ($getProject->registration_status == 'Open') {
            $getProject->update([
                'registration_status' => 'Closed',
            ]);
        } else if ($getProject->registration_status == 'Closed') {
            $getProject->update([
                'registration_status' => 'Closed',
            ]);
        }

        return $this->returnResponseApi(true,'Update project registration status successful','',200);
    }

    /**
     * Delete Project Header
     * @param int $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function delete(int $id)
    {
        $getProject = ProjectHeader::where('id', $id)->first();
        if (!$getProject) {
            return $this->returnResponseApi(false,'Project Header Not Found','',404);
        }
        $getProject->delete();

        return $this->returnResponseApi(true,'Project Deleted Successful','',200);
    }
}
