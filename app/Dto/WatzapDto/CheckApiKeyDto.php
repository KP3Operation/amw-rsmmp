<?php

namespace App\Dto\WatzapDto;

use Spatie\LaravelData\Data;

class CheckApiKeyDto extends Data
{
    public function __construct(
        public bool $status,
        public ?string $message,
    ) {
    }
}
