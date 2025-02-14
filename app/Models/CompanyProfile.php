<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyProfile extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyProfileFactory> */
    use HasFactory;

    // Connection
    protected $connection = 'mysql';

    // Table name
    protected $table = 'company_profile';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'bp_code',
        'tax_id',
        'company_name',
        'company_status',
        'company_description',
        'company_url',
        'business_field',
        'sub_business_field',
        'product',
        'adr_line_1',
        'adr_line_2',
        'adr_line_3',
        'adr_line_4',
        'province',
        'city',
        'postal_code',
        'company_phone_1',
        'company_phone_2',
        'company_fax_1',
        'company_fax_2',
        'profile_verified_by',
        'profile_verified_at',
    ];

    /**
     * Get the user that owns the CompanyProfile
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
