<?php

namespace App\Dto\SimrsDto\Patient;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class ServiceUnitDataDto extends Data
{
    public function __construct(
        #[DataCollectionOf(ServiceUnitDto::class)]
        public DataCollection $data
    ) {
    }
}
