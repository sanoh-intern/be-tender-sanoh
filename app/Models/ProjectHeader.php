<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProjectHeader extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectHeaderFactory> */
    use HasFactory;

    // Connection
    protected $connection = 'mysql';

    // Table name
    protected $table = 'project_header';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'project_name',
        'project_status',
        'project_type',
        'project_description',
        'project_attach',
        'project_winner',
        'registration_status',
        'registration_due_at',
        'final_review_by',
        'final_review_at',
        'final_view_at',
        'created_by',
        'updated_by',
    ];

    /**
     * The userJoin that belong to the ProjectHeader
     */
    public function userJoin(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'list_user_project', 'project_header_id', 'user_id')->withTimestamps();
    }

    /**
     * The userWinner that belong to the ProjectHeader
     */
    public function userWinner(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'list_winner_project', 'project_header_id', 'user_id')->withTimestamps();
    }

    /**
     * Get all of the projectDetail for the ProjectHeader
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projectDetail(): HasMany
    {
        return $this->hasMany(ProjectDetail::class, 'project_header_id', 'id');
    }
}
