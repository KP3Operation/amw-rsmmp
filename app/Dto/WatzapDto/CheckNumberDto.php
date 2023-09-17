<?php

namespace App\Dto\WatzapDto;

use Spatie\LaravelData\Data;

class CheckNumberDto extends Data
{
    public function __construct(
        public string $status,
        public string $message,
        public string $ack
    ) {
    }
}
