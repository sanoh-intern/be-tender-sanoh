<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAuthResource extends JsonResource
{
    // Initialize variable
    protected $token;
    protected $user;

    // Constructor
    public function __construct($user, $token) {
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
            'role_id' => $this->user->role->pluck('id'),
            'role_tag' => $this->user->role->pluck('role_tag'),
            'access_token' => $this->whenNotNull($this->token),
            'token_type' => 'Bearer',
        ];
    }
}
