<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Sanctum\PersonalAccessToken;

class DashboardAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function loginStats()
    {
        // Initialize variable
        $data = [];
        $startOfDay = Carbon::today();
        $endOfDay = Carbon::today()->endOfDay();

        // Get the count of tokens created within the last hour
        $data['active_tokens'] = PersonalAccessToken::whereBetween('created_at', [$startOfDay,$endOfDay])->count();

        // Get the total count of users
        $data['total_users'] = User::count();

        // Get the count of active users where account_status is 1
        $data['active_users'] = User::where('account_status', 1)->count();

        // Get the count of deactivated users where account_status is 0
        $data['deactive_users'] = User::where('account_status', 0)->count();

        return response()->json([
            'success' => true,
            'message' => 'Dashboard Data Retrieved Successfully',
            'data' => $data,
        ]);
    }

    public function loginPerformance()
    {
        // initialize variable for monthly performance
        $startDate = now()->subMonth();
        $endDate = now();

        // Get the tokens created within the past month from today
        $monthly_tokens = PersonalAccessToken::whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Group the tokens by tokenable_id and count the logins for each user
        $monthly_login_data = $monthly_tokens->groupBy('tokenable_id')->map(function ($tokens) {
            $tokenable = $tokens->sortByDesc('tokenable_type')->first()->tokenable;

            return [
                'username' => ($tokenable && $tokenable->companyProfile) ? $tokenable->companyProfile->company_name : 'Unknown',
                'login_count' => $tokens->count(),
            ];
        });

        // initialize variable for daily performance
        $last24Hours = now()->subDay();

        // Get the tokens created within the last 24 hours
        $daily_tokens = PersonalAccessToken::where('created_at', '>=', $last24Hours)
            ->with('tokenable') // Load the related user
            ->get();

        // Group the tokens by tokenable_id and count the logins for each user
        $daily_login_data = $daily_tokens->groupBy('tokenable_id')->map(function ($tokens) {
            $tokenable = $tokens->first()->tokenable;

            return [
                'username' => ($tokenable && $tokenable->companyProfile) ? $tokenable->companyProfile->company_name : 'Unknown',
                'login_count' => $tokens->count(),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Login Data Retrieved Successfully',
            'data' => [
                'monthly' => $monthly_login_data->values(),
                'daily' => $daily_login_data->values(),
            ],
        ]);
    }

    public function userOnline()
    {
        // initialize variable
        $startOfDay = Carbon::today();
        $endOfDay = Carbon::today()->endOfDay();

        // Get the active tokens created within the last hour
        $active_tokens = PersonalAccessToken::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->with('tokenable')
            ->get();
            // Map the active tokens to the required details
            $active_token_details = $active_tokens->map(function ($token) {
            return [
                'username' => $token->tokenable->email,
                'name' => $token->tokenable->companyProfile->company_name,
                'role' => $token->tokenable->roleTag->role_tag,
                'last_login' => $token->created_at->format('d/m/Y - H:i:s'),
                'last_update' => $token->last_used_at ? $token->last_used_at->format('d/m/Y - H:i:s') : null,
                'id' => $token->id,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Active Token Details Retrieved Successfully',
            'data' => $active_token_details,
        ]);
    }

    public function userRevoke(Request $request) {
        // Validate the request to ensure 'token_id' is provided
        $request->validate([
            'token_id' => 'required|integer',
        ]);

        // Find the token by ID
        $token = PersonalAccessToken::find($request->token_id);

        if (! $token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found',
            ], 404);
        }

        // Revoke the specific token
        $token->delete();

        // Logout success response
        return response()->json([
            'success' => true,
            'message' => 'Token successfully revoked',
        ], 200);
    }
}
