<?php

namespace App\Dto\SimrsDto\Doctor;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Normalizers\ArrayableNormalizer;
use Spatie\LaravelData\Normalizers\ArrayNormalizer;
use Spatie\LaravelData\Normalizers\JsonNormalizer;
use Spatie\LaravelData\Normalizers\ModelNormalizer;
use Spatie\LaravelData\Normalizers\ObjectNormalizer;
use Spatie\LaravelData\Optional;

class InpatientListDto extends Data
{
    public function __construct(
        #[MapInputName('RegistrationNo')]
        public ?string $registrationNo,

        #[MapInputName('MedicalNo')]
        public ?string $medicalNo,

        #[MapInputName('PatientName')]
        public ?string $patientName,

        #[MapInputName('DateOfBirth')]
        public ?string $dateOfBirth,

        #[MapInputName('Age')]
        public ?string $age,

        #[MapInputName('Sex')]
        public ?string $sex,

        #[MapInputName('GuarantorName')]
        public ?string $guarantorName,

        #[MapInputName('RoomName')]
        public ?string $roomName,

        #[MapInputName('ClassName')]
        public ?string $className,

        #[MapInputName('EWS')]
        public InpatientListEwsDto $ews
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
