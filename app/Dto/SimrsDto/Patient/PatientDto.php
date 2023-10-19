<?php

namespace App\Dto\SimrsDto\Patient;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class PatientDto extends Data
{
    public function __construct(
        #[MapInputName('PatientID')]
        public string $patientId,

        #[MapInputName('MedicalNo')]
        public string $medicalNo,

        #[MapInputName('FirstName')]
        public string $firstName,

        #[MapInputName('MiddleName')]
        public string $middleName,

        #[MapInputName('LastName')]
        public string $lastName,

        #[MapInputName('DateOfBirth_yMdHms')]
        public string $birthDate,

        #[MapInputName('Email')]
        public string $email,

        #[MapInputName('Ssn')]
        public string $ssn,

        #[MapInputName('Sex')]
        public string $gender,

        #[MapInputName('GuarantorID')]
        public string $guarantorId
    )
    {}
}
