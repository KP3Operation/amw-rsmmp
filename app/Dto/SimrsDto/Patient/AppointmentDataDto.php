<?php

namespace App\Dto\SimrsDto\Patient;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class AppointmentDataDto extends Data
{
    public function __construct(
        #[DataCollectionOf(AppointmentDto::class)]
        public DataCollection $data
    ) {
    }
}
