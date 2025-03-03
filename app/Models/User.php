<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    // Connection
    protected $connection = 'mysql';

    // Table name
    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'company_photo',
        'role_id',
        'email',
        'password',
        'account_status',
        'profile_verified_at',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The roles that belong to the User
     */
    public function role(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'list_user_role', 'user_id', 'role_id')->withPivot('created_at')->withTimestamps();
    }

    /**
     * Get the roleTag that owns the User
     */
    public function roleTag(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * Get the user associated with the User
     */
    public function companyProfile(): HasOne
    {
        return $this->hasOne(CompanyProfile::class, 'user_id', 'id');
    }

    /**
     * The userProject that belong to the User
     */
    public function userProject(): BelongsToMany
    {
        return $this->belongsToMany(ProjectHeader::class, 'list_user_project', 'user_id', 'project_header_id')->withTimestamps();
    }
}
