<?php

namespace App\Dto\SimrsDto;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class DoctorDataDto extends Data
{
    public function __construct(
        #[DataCollectionOf(DoctorDto::class)]
        public DataCollection $data
    )
    {}
}
