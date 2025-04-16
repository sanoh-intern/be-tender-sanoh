<?php

namespace App\Http\Middleware;

use Closure;
use App\Trait\ResponseApi;
use Illuminate\Http\Request;
use App\Models\PasswordResetTokens;
use Illuminate\Support\Facades\Cache;
use Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureResetTokenIsValid
{
    /**
     * -------TRAIT---------
     * Mandatory:
     * 1. ResponseApi = Response api should use ResponseApi trait template
     */
    use ResponseApi;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $email = $request->input('email');
        $token = $request->input('token');
        $cacheToken = Cache::get("reset-password-$email");

        $recordToken = PasswordResetTokens::where('email', $email)->where('token', $token)->first();
        // Check record
        if (! $recordToken) {
            return $this->returnResponseApi(false, 'Token Not Found', null, 404);
        }

        // Check if token valid
        if ($token != $cacheToken && $cacheToken == null && $recordToken->status == 0) {
            return $this->returnResponseApi(false, 'Token Invalid', null, 404);
        }

        // Action after pass checking
        Cache::forget("reset-password-$email");
        $recordToken->delete();

        return $next($request);
    }
}
