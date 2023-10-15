<?php

namespace App\Dto\SimrsDto\Patient;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class DoctorScheduleDataDto extends Data
{
    public function __construct(
        #[DataCollectionOf(DoctorScheduleDto::class)]
        public DataCollection $data
    )
    {}
}
