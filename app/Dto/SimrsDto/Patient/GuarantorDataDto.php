<?php

namespace App\Dto\SimrsDto\Patient;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class GuarantorDataDto extends Data
{
    public function __construct(
        #[DataCollectionOf(GuarantorDto::class)]
        public DataCollection $data
    ) {}
}
