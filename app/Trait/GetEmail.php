<?php

namespace App\Trait;

use App\Models\User;

trait GetEmail
{
    /**
     * Get email based on role_tag
     * can accept more than one role using commas symbol. ex: getEmailByRole('a','b');
     * @param array $roleTag
     * @return \Illuminate\Database\Eloquent\Collection<int, User>
     */
    public function getEmailByRole(...$roleTag) {
        $getEmail = User::whereHas('roleTag', function ($query) use($roleTag) {
            $query->whereIn('role_tag', $roleTag);
        })
        ->get('email');

        return $getEmail;
    }
}
