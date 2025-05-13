<?php

namespace App\Http\Controllers\Api\V1\IntegrityPact;

use App\Trait\StoreFile;
use App\Trait\ResponseApi;
use App\Models\IntegrityPact;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\IntegrityPact\IntegrityPactCreateRequest;
use App\Http\Requests\IntegrityPact\IntegrityPactUpdateRequest;

class IntegrityPactController extends Controller
{
    /**
     * -------TRAIT---------
     * Mandatory:
     * 1. ResponseApi = Response api should use ResponseApi trait template
     * 2. StoreFile = Save file to server storage
     */
    use ResponseApi, StoreFile;

    /**
     * Create Integrity Pact record
     * @param \App\Http\Requests\IntegrityPact\IntegrityPactCreateRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function createIntegrityPact(IntegrityPactCreateRequest $request)
    {
        $request->validated();

        DB::transaction(function () use ($request) {
            if ($request->hasFile('integrity_pact_file')) {
                $filePath = $this->saveFile($request->file('integrity_pact_file'), 'integrity_pact', 'Documents', 'integrity_pact', 'local');
            } else {
                $filePath = null;
            }

            IntegrityPact::create([
                'user_id' => Auth::user()->id,
                'integrity_pact_file' => $filePath,
                'integrity_pact_desc' => $request->integrity_pact_desc,
            ]);
        });

        return $this->returnResponseApi(true, 'Create Integrity Pact Success', null, 201);
    }

    /**
     * Update Integrity pact resource
     * @param \App\Http\Requests\IntegrityPact\IntegrityPactUpdateRequest $request
     * @param \App\Models\IntegrityPact $integrityPact
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(IntegrityPactUpdateRequest $request, IntegrityPact $integrityPact)
    {
        $request->validated();
        if ($integrityPact->user_id == Auth::user()->id) {
            if ($request->hasFile('integrity_pact_file')) {
                $filePath = $this->saveFile($request->file('integrity_pact_file'), 'integrity_pact', 'Documents', 'integrity_pact', 'local');
                $oldFile = $this->deleteFile($integrityPact->integrity_pact_file, 'local');
                if ($oldFile == false) {
                    return $this->returnResponseApi(false, 'Old File Not Found', '', 404);
                }
            }

            $integrityPact->update([
                'integrity_pact_file' => $filePath ?? $integrityPact->integrity_pact_file,
                'integrity_pact_desc' => $request->integrity_pact_desc ?? $integrityPact->integrity_pact_desc,
            ]);
        } else {
            return $this->returnResponseApi(true, 'You are not authorized to access this resource.', null, 403);
        }

        return $this->returnResponseApi(true, 'Update Integrity Pact Data Successful', null, 200);
    }

    /**
     * Delete integrity pact resource
     * @param \App\Models\IntegrityPact $integrityPact
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(IntegrityPact $integrityPact)
    {
        if ($integrityPact->user_id == Auth::user()->id) {
            $this->deleteFile($integrityPact->integrity_pact_file);
            $integrityPact->delete();
        } else {
            return $this->returnResponseApi(true, 'You are not authorized to access this resource.', null, 403);
        }

        return $this->returnResponseApi(true, 'Delete Integrity Pact Data Successful', null, 200);
    }
}
