<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;

    // Connection
    protected $connection = 'mysql';

    // Table name
    protected $table = 'role';

    // Fillable column
    protected $fillable = [
        'role_tag',
    ];

    /**
     * The roles that belong to the Role
     */
    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'list_user_role', 'role_id', 'user_id');
    }
}
