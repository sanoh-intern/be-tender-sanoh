<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerifyNotification extends Model
{
    // Connection
    protected $connection = 'mysql';

    // Table name
    protected $table = 'verify_notification';

    // Fillable column
    protected $fillable = [
        'user_id',
        'category',
        'description',
        'status',
        'message',
        'verify_by',
        'verify_at',
        'expires_at',
    ];

    /**
     * Get the companyProfile that owns the VerifyNotification
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function companyProfile(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'user_id', 'user_id');
    }

    /**
     * Get the user that owns the VerifyNotification
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
