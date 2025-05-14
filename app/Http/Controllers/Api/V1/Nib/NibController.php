<?php

namespace App\Http\Controllers\Api\V1\Nib;

use App\Http\Requests\Nib\NibUpdateRequest;
use App\Models\Nib;
use App\Trait\StoreFile;
use App\Trait\ResponseApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Nib\NibCreateRequest;

class NibController extends Controller
{
    /**
     * -------TRAIT---------
     * Mandatory:
     * 1. ResponseApi = Response api should use ResponseApi trait template
     * 2. StoreFile = Save file to server storage
     */
    use ResponseApi, StoreFile;

    /**
     * Create new nib resource
     * @param \App\Http\Requests\Nib\NibCreateRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function createNib(NibCreateRequest $request)
    {
        $request->validated();

        DB::transaction(function () use ($request) {
            if ($request->hasFile('nib_file')) {
                $filePath = $this->saveFile($request->file('nib_file'), 'nib', 'Documents', 'nib', 'local');
            } else {
                $filePath = null;
            }

            Nib::create([
                'user_id' => Auth::user()->id,
                'nib_file' => $filePath,
                'nib_number' => $request->nib_number,
                'issuing_agency' => $request->issuing_agency,
                'issuing_date' => $request->issuing_date,
                'investment_status' => $request->investment_status,
                'kbli' => $request->kbli,
            ]);
        });

        return $this->returnResponseApi(true, 'Create NIB Success', null, 201);
    }

    /**
     * Update nib resource
     * @param \App\Http\Requests\Nib\NibUpdateRequest $request
     * @param \App\Models\Nib $nib
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(NibUpdateRequest $request, Nib $nib)
    {
        $request->validated();
        if ($nib->user_id == Auth::user()->id) {
            if ($request->hasFile('nib_file')) {
                $filePath = $this->saveFile($request->file('nib_file'), 'nib', 'Documents', 'nib', 'local');
                $oldFile = $this->deleteFile($nib->nib_file, 'local');
                if ($oldFile == false) {
                    return $this->returnResponseApi(false, 'Old File Not Found', '', 404);
                }
            }

            $nib->update([
                'nib_file' => $filePath ?? $nib->nib_file,
                'nib_number' => $request->nib_number ?? $nib->nib_number,
                'issuing_agency' => $request->issuing_agency ?? $nib->issuing_agency,
                'issuing_date' => $request->issuing_date ?? $nib->issuing_date,
                'investment_status' => $request->investment_status ?? $nib->investment_status,
                'kbli' => $request->kbli ?? $nib->kbli,
            ]);
        } else {
            return $this->returnResponseApi(true, 'You are not authorized to access this resource.', null, 403);
        }

        return $this->returnResponseApi(true, 'Update NIB Data Successful', null, 200);
    }

    /**
     * Delete nib resource
     * @param \App\Models\Nib $nib
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Nib $nib)
    {
        if ($nib->user_id == Auth::user()->id) {
            $this->deleteFile($nib->nib_file);
            $nib->delete();
        } else {
            return $this->returnResponseApi(true, 'You are not authorized to access this resource.', null, 403);
        }

        return $this->returnResponseApi(true, 'Delete NIB Data Successful', null, 200);
    }
}
