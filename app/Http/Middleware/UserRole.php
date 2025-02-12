<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserRole
{
    /**
     * Check user permissible role before accessing the route
     *
     * @param  mixed  $request
     * @param  string  $isRole  this parameter will assign the permisible role
     */
    public function handle($request, Closure $next, string $isRole)
    {
        // Initialize variable
        $user = $request->user();
        $role = [];
        $role[] = $isRole;

        // Get the user role from request
        $userRole = $user->role->pluck('role_tag')->toArray();

        // Match the array from variable role and userrole
        $rolesAllowed = array_intersect($role, $userRole);

        // Validation
        if (empty($rolesAllowed)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
