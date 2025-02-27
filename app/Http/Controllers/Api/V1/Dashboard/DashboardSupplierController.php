<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\DashboardMiniProfileResource;
use App\Models\User;
use App\Trait\ResponseApi;
use Auth;

class DashboardSupplierController extends Controller
{
    /**
     * -------TRAIT---------
     * Mandatory:
     * 1. ResponseApi = Response api should use ResponseApi trait template
     */
    use ResponseApi;

    /**
     * Show user mini profile
     *
     * @param  int  $id  this is id user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function miniProfile()
    {
        $user = User::with('companyProfile')->where('id', Auth::user()->id)->first();
        if (! $user) {
            return $this->returnResponseApi(false, 'User Not Found', '', 404);
        }

        return $this->returnResponseApi(true, 'Mini Profile Successful', new DashboardMiniProfileResource($user), 200);
    }
}
