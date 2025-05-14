<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nib extends Model
{
    // Connection
    protected $connection = 'mysql';

    // Table name
    protected $table = 'nib';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'nib_number',
        'nib_file',
        'issuing_agency',
        'issuing_date',
        'investment_status',
        'kbli',
        'nib_verified_by',
        'nib_verified_at',
    ];
}
