<?php

namespace App\Trait;

use App\Models\User;

trait CheckRegistration
{
    public function isRegis(int $user_id, int $project_id) : bool {
        return User::whereHas('userProject', function ($query) use ($project_id) {
            $query->where('project_header_id', $project_id);
        })->where('id', $user_id)->exists();
    }
}
