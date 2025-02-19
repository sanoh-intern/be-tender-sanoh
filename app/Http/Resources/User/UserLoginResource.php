<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLoginResource extends JsonResource
{
    // Initialize variable
    protected $token;

    protected $user;

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
            'email' => $this->user->email,
            'role_id' => (string) $this->user->role_id,
            'role_tags' => $this->user->roleTag->role_tag,
            'access_token' => $this->whenNotNull($this->token),
            'token_type' => 'Bearer',
        ];
    }
}
