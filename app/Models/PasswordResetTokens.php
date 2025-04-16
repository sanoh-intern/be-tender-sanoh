<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetTokens extends Model
{
     // Connection
     protected $connection = 'mysql';

     // Table name
     protected $table = 'password_reset_tokens';

    // Timestamps
    public $timestamps = false;

     /**
      * The attributes that are mass assignable.
      *
      * @var list<string>
      */
     protected $fillable = [
        'email',
        'token',
        'created_at',
     ];
}
