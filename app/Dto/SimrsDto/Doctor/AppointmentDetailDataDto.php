<?php

namespace App\Dto\SimrsDto\Doctor;

use Spatie\LaravelData\Data;

class AppointmentDetailDataDto extends Data
{
    public function __construct(
        public AppointmentDetailDto $data
    ) {
    }
}
