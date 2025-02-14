<?php

namespace App\Trait;

use Illuminate\Support\Facades\Auth;

trait AuthorizationRole
{
    /**
     * For role cheking.
     * When you need to check what role have the access to the function.
     * can accept more than one role using commas symbol. ex: permissibleRole('a','b');
     */
    public function permissibleRole(string ...$role)
    {
        $getRole = Auth::user()->role->pluck('role_tag');

        $check = $getRole->intersect($role)->isNotEmpty();

        return $check;
    }
}
