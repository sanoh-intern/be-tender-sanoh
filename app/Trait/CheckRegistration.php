<?php

namespace App\Trait;

use App\Models\User;

trait CheckRegistration
{
    /**
     * Checking user if already join the project
     */
    public function isRegis(int $userId, int $projectId): bool
    {
        return User::whereHas('userProject', function ($query) use ($projectId) {
            $query->where('project_header_id', $projectId);
        })->where('id', $userId)->exists();
    }
}
