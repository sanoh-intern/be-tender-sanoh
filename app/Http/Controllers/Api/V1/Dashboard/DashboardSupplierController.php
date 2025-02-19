<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Resources\Dashboard\DashboardMiniProfileResource;
use App\Models\User;
use App\Trait\ResponseApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardSupplierController extends Controller
{
    /**
     * -------TRAIT---------
     * Mandatory:
     * 1. ResponseApi = Response api should use ResponseApi trait template
     */
    use ResponseApi;

    public function miniProfile(int $id)
    {
        $user = User::with('companyProfile')->where('id', $id)->first();
        if (! $user) {
            return $this->returnResponseApi(false, 'User Not Found', '', 404);
        }

        return $this->returnResponseApi(true, 'Mini Profile Successful', new DashboardMiniProfileResource($user), 200);
    }
}
