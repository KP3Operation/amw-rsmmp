<?php

namespace App\Dto\SimrsDto\Doctor;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Normalizers\ArrayableNormalizer;
use Spatie\LaravelData\Normalizers\ArrayNormalizer;
use Spatie\LaravelData\Normalizers\JsonNormalizer;
use Spatie\LaravelData\Normalizers\ModelNormalizer;
use Spatie\LaravelData\Normalizers\ObjectNormalizer;

class DoctorFeeByPaymentDateDto extends Data
{
    public function __construct(
        #[MapInputName('RegistrationNo')]
        public ?string $registrationNo,

        #[MapInputName('MedicalNo')]
        public ?string $medicalNo,
        #[MapInputName('PatientName')]
        public ?string $patientName,

        #[MapInputName('ItemName')]
        public ?string $itemName,

        #[MapInputName('Qty')]
        public int $qty,

        #[MapInputName('GuarantorName')]
        public ?string $guarantorName,

        #[MapInputName('Amount')]
        public int $amount
    ) {
    }

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
