<?php

namespace App\Http\Resources\Verification;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VerificationStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status_verification' => $this['status_verification'],
            'updated_at' => Carbon::parse($this['updated_at'])->format('Y-m-d h:i'),
        ];
    }
}
