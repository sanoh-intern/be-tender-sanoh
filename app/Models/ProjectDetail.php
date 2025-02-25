<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectDetail extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectDetailFactory> */
    use HasFactory;

    // Connection
    protected $connection = 'mysql';

    // Table name
    protected $table = 'project_detail';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'project_header_id',
        'supplier_id',
        'proposal_attach',
        'proposal_total_amount',
        'proposal_revision_no',
        'proposal_status',
        'proposal_comment',
        'proposal_revision_on',
        'reviewed_by',
        'reviewed_at',
    ];

    /**
     * Get the projectHeader that owns the ProjectDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function projectHeader(): BelongsTo
    {
        return $this->belongsTo(ProjectHeader::class, 'project_header_id', 'id');
    }
}
