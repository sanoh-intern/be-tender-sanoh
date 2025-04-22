<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'expires_at',
    ];
}
