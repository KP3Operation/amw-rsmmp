<?php

namespace App\Dto\SimrsDto\Patient;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Normalizers\ArrayableNormalizer;
use Spatie\LaravelData\Normalizers\ArrayNormalizer;
use Spatie\LaravelData\Normalizers\JsonNormalizer;
use Spatie\LaravelData\Normalizers\ModelNormalizer;
use Spatie\LaravelData\Normalizers\ObjectNormalizer;

class DoctorScheduleDto extends Data
{

    public function __construct(
        #[MapInputName('ServiceUnitID')]
        public ?string $serviceUnitID,

        #[MapInputName('ServiceUnitName')]
        public ?string $serviceUnitName,

        #[MapInputName('ParamedicID')]
        public ?string $paramedicID,

        #[MapInputName('ParamedicName')]
        public ?string $paramedicName,

        #[MapInputName('ScheduleDate')]
        public ?string $scheduleDate,

        #[MapInputName('OperationalTimeName')]
        public ?string $operationalTimeName,

        #[MapInputName('StartTime1')]
        public ?string $startTime1,

        #[MapInputName('EndTime1')]
        public ?string $endTime1,

        #[MapInputName('StartTime2')]
        public ?string $startTime2,

        #[MapInputName('EndTime3')]
        public ?string $endTime3,

        #[MapInputName('StartTime4')]
        public ?string $startTime4,

        #[MapInputName('EndTime5')]
        public ?string $endTime5,

        #[MapInputName('ScheduleDate_yMdHms')]
        public ?string $ScheduleDate_yMdHms,

    ) {}

    public static function normalizers(): array
    {
        return [
            ModelNormalizer::class,
            ArrayableNormalizer::class,
            ObjectNormalizer::class,
            ArrayNormalizer::class,
            JsonNormalizer::class
        ];
    }
}
