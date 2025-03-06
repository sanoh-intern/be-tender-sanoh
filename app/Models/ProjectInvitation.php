<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectInvitation extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectInvitationFactory> */
    use HasFactory;

    // Connection
    protected $connection = 'mysql';

    // Table name
    protected $table = 'invitation_user_project';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'project_header_id',
        'invitation_by',
    ];

    /**
     * Get the user that owns the ProjectInvitation
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the ProjectHeader that owns the ProjectInvitation
     */
    public function projectHeader(): BelongsTo
    {
        return $this->belongsTo(ProjectHeader::class, 'project_header_id', 'id');
    }
}
