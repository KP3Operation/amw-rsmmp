<?php

namespace App\Dto\SimrsDto\Doctor;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Normalizers\ArrayableNormalizer;
use Spatie\LaravelData\Normalizers\ArrayNormalizer;
use Spatie\LaravelData\Normalizers\JsonNormalizer;
use Spatie\LaravelData\Normalizers\ModelNormalizer;
use Spatie\LaravelData\Normalizers\ObjectNormalizer;

class InpatientListEwsDto extends Data
{
    public function __construct(
        #[MapInputName('Status')]
        public int $status,

        #[MapInputName('VitalSignInitial')]
        public ?string $vitalSignInitial,

        #[MapInputName('VitalSignName')]
        public ?string $vitalSignName,

        #[MapInputName('Value')]
        public int $value,

        #[MapInputName('VitalSignUnit')]
        public ?string $vitalSignUnit,

    ) {
    }

    public static function normalizers(): array
    {
        return [
            ModelNormalizer::class,
            ArrayableNormalizer::class,
            ObjectNormalizer::class,
            ArrayNormalizer::class,
            JsonNormalizer::class,
        ];
    }
}
