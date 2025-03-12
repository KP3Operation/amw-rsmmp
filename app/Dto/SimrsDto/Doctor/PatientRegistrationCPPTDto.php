<?php

namespace App\Dto\SimrsDto\Doctor;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Normalizers\ArrayableNormalizer;
use Spatie\LaravelData\Normalizers\ArrayNormalizer;
use Spatie\LaravelData\Normalizers\JsonNormalizer;
use Spatie\LaravelData\Normalizers\ModelNormalizer;
use Spatie\LaravelData\Normalizers\ObjectNormalizer;

class PatientRegistrationCPPTDto extends Data
{
    public function __construct(
        #[MapInputName('RegistrationNo')]
        public ?string $registrationNo,

        #[MapInputName('DateTimeInfo')]
        public ?string $dateTimeInfo,

        #[MapInputName('CreatedByUserID')]
        public ?string $createdByUserID,

        #[MapInputName('CreatedByUserName')]
        public ?string $createdByUserName,

        #[MapInputName('ServiceUnitName')]
        public ?string $serviceUnitName,

        #[MapInputName('SRMedicalNotesInputType')]
        public ?string $sRMedicalNotesInputType,

        #[MapInputName('Info1')]
        public ?string $info1,

        #[MapInputName('Info2')]
        public ?string $info2,

        #[MapInputName('Info3')]
        public ?string $info3,

        #[MapInputName('Info4')]
        public ?string $info4,

        #[MapInputName('Info5')]
        public ?string $info5,

        #[MapInputName('PpaInstruction')]
        public ?string $ppaInstruction,

        #[MapInputName('DpjpNotes')]
        public ?string $dpjpNotes,

        #[MapInputName('IsDeleted')]
        public ?bool $isDeleted,

        #[MapInputName('DateTimeInfo_yMdHms')]
        public ?string $dateTimeInfo_yMdHms
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
