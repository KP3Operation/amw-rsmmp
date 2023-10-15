<?php

namespace App\Dto\SimrsDto\Patient;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Normalizers\ArrayableNormalizer;
use Spatie\LaravelData\Normalizers\ArrayNormalizer;
use Spatie\LaravelData\Normalizers\JsonNormalizer;
use Spatie\LaravelData\Normalizers\ModelNormalizer;
use Spatie\LaravelData\Normalizers\ObjectNormalizer;

class PatientEncounterDetailDto extends Data
{

    public function __construct(
        #[MapInputName('RegistrationNo')]
        public string $registrationNo,

        #[MapInputName('SRRegistrationType')]
        public string $sRRegistrationType,

        #[MapInputName('RegistrationDate')]
        public string $registrationDate,

        #[MapInputName('RegistrationTime')]
        public string $registrationTime,

        #[MapInputName('RegistrationQue')]
        public string $registrationQue,

        #[MapInputName('ActualVisitDate')]
        public ?string $actualVisitDate,

        #[MapInputName('RegistrationNo1')]
        public string $registrationNo1,

        #[MapInputName('FromRegistrationNo')]
        public string $fromRegistrationNo,

        #[MapInputName('ParamedicID')]
        public string $paramedicID,

        #[MapInputName('ParamedicName')]
        public string $paramedicName,

        #[MapInputName('ServiceUnitID')]
        public string $serviceUnitID,

        #[MapInputName('ServiceUnitName')]
        public string $serviceUnitName,

        #[MapInputName('VisitTypeID')]
        public string $visitTypeID,

        #[MapInputName('VisitTypeName')]
        public string $visitTypeName,

        #[MapInputName('Notes')]
        public string $notes,

        #[MapInputName('DiagnoseID')]
        public ?string $diagnoseID,

        #[MapInputName('DiagnosisText')]
        public ?string $diagnosisText,

        #[MapInputName('DischargeDate')]
        public ?string $dischargeDate,

        #[MapInputName('DischargeTime')]
        public ?string $dischargeTime,

        #[MapInputName('DischargeNotes')]
        public ?string $dischargeNotes,

        #[MapInputName('DischargeMedicalNotes')]
        public string $dischargeMedicalNotes,

        #[MapInputName('RegistrationDate_yMdHms')]
        public string $registrationDate_yMdHms,

        #[MapInputName('ActualVisitDate_yMdHms')]
        public ?string $actualVisitDate_yMdHms,

        #[MapInputName('GuarantorID')]
        public ?string $guarantorID,

        #[MapInputName('GuarantorName')]
        public ?string $guarantorName,

        #[MapInputName('MedicalNo')]
        public ?string $medicalNo,

        #[MapInputName('Sex')]
        public ?string $sex,

        #[MapInputName('DateOfBirth')]
        public ?string $dateOfBirth,

        #[MapInputName('FirstName')]
        public ?string $firstName,

        #[MapInputName('MiddleName')]
        public ?string $middleName,

        #[MapInputName('LastName')]
        public ?string $lastName,

        #[MapInputName('IsNewPatient')]
        public bool $isNewPatient,

        #[MapInputName('DischargeDate_yMdHms')]
        public ?string $dischargeDate_yMdHms,

        #[MapInputName('DateOfBirth_yMdHms')]
        public ?string $dateOfBirth_yMdHms,

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
