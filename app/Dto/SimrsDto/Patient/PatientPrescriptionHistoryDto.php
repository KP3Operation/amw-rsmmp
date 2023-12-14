<?php

namespace App\Dto\SimrsDto\Patient;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Normalizers\ArrayableNormalizer;
use Spatie\LaravelData\Normalizers\ArrayNormalizer;
use Spatie\LaravelData\Normalizers\JsonNormalizer;
use Spatie\LaravelData\Normalizers\ModelNormalizer;
use Spatie\LaravelData\Normalizers\ObjectNormalizer;

class PatientPrescriptionHistoryDto extends Data
{
    public function __construct(
        #[MapInputName('PrescriptionNo')]
        public ?string $PrescriptionNo,

        #[MapInputName('MedicalNo')]
        public ?string $medicalNo,

        #[MapInputName('RegistrationNo')]
        public ?string $registrationNo,

        #[MapInputName('ParamedicID')]
        public ?string $paramedicId,

        #[MapInputName('ParamedicName')]
        public ?string $paramedicName,

        #[MapInputName('PrescriptionDate')]
        public ?string $prescriptionDate,

        #[MapInputName('PrescriptionDate_yMdHms')]
        public ?string $prescriptionDate_yMdHms
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
