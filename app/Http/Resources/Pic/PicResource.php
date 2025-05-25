<?php

namespace App\Http\Resources\Pic;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'pic_id' => $this->pic_id ?? null,
            'job_position' => $this->job_position ?? null,
            'department' => $this->department ?? null,
            'pic_name' => $this->pic_name ?? null,
            'pic_telp_number_1' => $this->pic_telp_number_1 ?? null,
            'pic_telp_number_2' => $this->pic_telp_number_2 ?? null,
            'pic_email_1' => $this->pic_email_1 ?? null,
            'pic_email_2' => $this->pic_email_2 ?? null,
        ];
    }
}
