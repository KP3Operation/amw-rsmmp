<?php

namespace App\Dto\SimrsDto\Patient;

use Spatie\LaravelData\Data;

class CreateAppointmentDataDto extends Data
{
    public function __construct(
        public AppointmentDto $data
    ) {
    }
}
