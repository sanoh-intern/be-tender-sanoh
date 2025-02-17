<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'user_id',
        'project_header_id',
        'invitation_by'
    ];
}
