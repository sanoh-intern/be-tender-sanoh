<?php

namespace App\Http\Resources\Verification;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VerifcationListHistoryuserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'verification_id' => $this->id,
            'request_date' => Carbon::parse($this->created_at)->format('y-m-d'),
            'status' => $this->castStatus(),
            'message' => $this->message ?? null,
        ];
    }

    private function castStatus() {
        $status = $this->status;

        switch ($status) {
            case 'Accepted':
                $cast = 'Approved';
                break;

                case 'Declined':
                $cast = 'Rejected';
                break;

                case null:
                $cast = 'Pending';
                break;
        }

        return $cast;
    }
}
