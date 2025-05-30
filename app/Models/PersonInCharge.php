<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonInCharge extends Model
{
    /** @use HasFactory<\Database\Factories\PersonInChargeFactory> */
    use HasFactory;

    // Connection
    protected $connection = 'mysql';

    // Table name
    protected $table = 'person_in_charge';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'job_position',
        'department',
        'pic_name',
        'pic_telp_number_1',
        'pic_telp_number_2',
        'pic_email_1',
        'pic_email_2',
    ];
}
