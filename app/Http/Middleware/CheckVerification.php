<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Models\Nib;
use Illuminate\Http\Request;
use App\Models\IntegrityPact;
use App\Models\CompanyProfile;
use App\Models\BusinessLicense;
use Symfony\Component\HttpFoundation\Response;

class CheckVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = Auth::user()->id;
        CompanyProfile::where('user_id', $userId)->whereNull('profile_verified_at')->first();
        BusinessLicense::where('user_id', $userId)->whereNull('business_license_verified_at')->get();
        IntegrityPact::where('user_id', $userId)->whereNull('integrity_pact_verified_at')->first();
        Nib::where('user_id', $userId)->whereNull('nib_verified_at')->first();

        return $next($request);
    }
}
