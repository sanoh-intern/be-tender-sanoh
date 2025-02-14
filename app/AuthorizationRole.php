<?php

namespace App;

use Illuminate\Support\Facades\Auth;

trait AuthorizationRole
{
    /**
     * For role cheking.
     * When you need to check what role have the access to the function
     * @param string $role
     */
    public function permissibleRole(string $role) {
        $getRole = Auth::user()->role->pluck('role_tag');

        $check = $getRole->contains($role);

        return $check;
    }
}
