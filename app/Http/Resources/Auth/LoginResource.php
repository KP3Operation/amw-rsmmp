<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'phoneNumber' => $this->resource->phoneNumber,
            'otpCreatedAt' => $this->resource->otpCreatedAt,
            'otpUpdatedAt' => $this->resource->otpUpdatedAt,
            'otpTimeout' => $this->resource->otpTimeout,
        ];
    }
}
