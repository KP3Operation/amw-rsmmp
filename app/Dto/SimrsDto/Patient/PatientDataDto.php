<?php

namespace App\Dto\SimrsDto\Patient;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class PatientDataDto extends Data
{
    public function __construct(
        #[DataCollectionOf(PatientDto::class)]
        public DataCollection $data
    )
    {}
}
