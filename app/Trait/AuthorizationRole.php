<?php

namespace App\Trait;

use App\Models\Role;
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
        $user = Auth::user()->role_id;

        $getRole = Role::where('id', $user)->pluck('role_tag');
        
        $check = $getRole->intersect($role)->isNotEmpty();

        return $check;
    }
}
