<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntegrityPact extends Model
{
    // Connection
    protected $connection = 'mysql';

    // Table name
    protected $table = 'integrity_pact';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'integrity_pact_file',
        'integrity_pact_desc',
        'integrity_pact_verified_by',
        'integrity_pact_verified_at',
    ];
}
