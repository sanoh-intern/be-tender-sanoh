<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Requests\PersonInCharge\PicCreateRequest;
use App\Http\Requests\PersonInCharge\PicUpdateRequest;
use App\Models\PersonInCharge;
use App\Trait\ResponseApi;
use Auth;
use DB;

class PersonInChargeController
{
    /**
     * -------TRAIT---------
     * Mandatory:
     * 1. ResponseApi = Response api should use ResponseApi trait template
     */
    use ResponseApi;

    /**
     * Create new resource for person_in_charge table
     * @param \App\Http\Requests\PersonInCharge\PicCreateRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function createPic(PicCreateRequest $request)
    {
        $request->validated();
        $userId = Auth::user()->id;

        DB::transaction(function () use ($request, $userId) {
            foreach ($request['data'] as $data) {
                PersonInCharge::create([
                    'user_id' => $userId,
                    'job_position' => $data['job_position'],
                    'departement' => $data['departement'],
                    'pic_name' => $data['pic_name'],
                    'pic_telp_number_1' => $data['pic_telp_number_1'],
                    'pic_telp_number_2' => $data['pic_telp_number_2'],
                    'pic_email_1' => $data['pic_email_1'],
                    'pic_email_2' => $data['pic_email_2'],
                ]);
            }
        });

        return $this->returnResponseApi(true, 'Create PIC Data Successful', null, 201);
    }

    /**
     * Update PIC Resource
     * @param \App\Http\Requests\PersonInCharge\PicUpdateRequest $request
     * @param \App\Models\PersonInCharge $personInCharge
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(PicUpdateRequest $request, PersonInCharge $personInCharge)
    {
        $personInCharge->update($request->validated());

        return $this->returnResponseApi(true, 'Update PIC Data Successful', null, 200);
    }

    /**
     * Delete PIC Resource
     * @param \App\Models\PersonInCharge $personInCharge
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(PersonInCharge $personInCharge)
    {
        if ($personInCharge->user_id == Auth::user()->id) {
            $personInCharge->delete();
        } else {
            return $this->returnResponseApi(true, 'You are not authorized to access this resource.', null, 403);
        }

        return $this->returnResponseApi(true, 'Delete PIC Data Successful', null, 200);
    }
}
