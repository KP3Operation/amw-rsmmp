<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'phone_number' => $this->phone_number,
                'otp_created_at' => $this->otp_created_at,
                'otp_updated_at' => $this->otp_updated_at,
                'otp_timeout' => $this->otp_timeout
            ],
            'links' => [
                'self' => null,
            ],
        ];
    }
}
