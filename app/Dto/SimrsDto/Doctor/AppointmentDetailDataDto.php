<?php

namespace App\Dto\SimrsDto\Doctor;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class AppointmentDetailDataDto extends Data
{
    public function __construct(
        public AppointmentDetailDto $data
    )
    {}
}
