<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessLicense extends Model
{
    // Connection
    protected $connection = 'mysql';

    // Table name
    protected $table = 'business_license';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'business_license_number',
        'business_license_file',
        'business_type',
        'qualification',
        'sub_classification',
        'issuing_agency',
        'issuing_date',
        'expiry_date',
        'business_license_verified_by',
        'business_license_verified_at',

    ];
}
