<?php

namespace App\Dto\SimrsDto\Doctor;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Normalizers\ArrayableNormalizer;
use Spatie\LaravelData\Normalizers\ArrayNormalizer;
use Spatie\LaravelData\Normalizers\JsonNormalizer;
use Spatie\LaravelData\Normalizers\ModelNormalizer;
use Spatie\LaravelData\Normalizers\ObjectNormalizer;

class InpatientListDto extends Data
{
    public function __construct(
        #[MapInputName('RegistrationNo')]
        public int $status,
        #[MapInputName('MedicalNo')]
        public int $medicalNo,
        #[MapInputName('PatientName')]
        public int $patientName,
        #[MapInputName('GuarantorName')]
        public int $guarantorName,
        #[MapInputName('RoomName')]
        public int $roomName,
        #[MapInputName('ClassName')]
        public int $className,
        #[MapInputName('EWS')]
        public InpatientListEwsDto $ews
    )
    {}

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
