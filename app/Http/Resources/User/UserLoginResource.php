<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLoginResource extends JsonResource
{
    // Initialize variable
    protected $token;
    protected $user;
    public static $wrap = false;

    // Constructor
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => true,
            'company_profile' => $this->user->companyProfile->company_photo ? asset('storage/'.$this->user->companyProfile->company_photo) : null,
            'company_name' => $this->user->companyProfile->company_name ?? 'No Name',
            'bp_code' => $this->user->companyProfile->bp_code ?? 'Non Verified User',
            'email' => $this->user->email,
            'role_id' => (string) $this->user->role_id,
            'role_tags' => $this->user->roleTag->role_tag,
            'profile_verified_at' => !empty($this->user->companyProfile->profile_verified_at) ? true : false,
            'access_token' => $this->whenNotNull($this->token),
            'token_type' => 'Bearer',
        ];
    }
}
