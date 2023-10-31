<?php

namespace App\Dto\SimrsDto\Doctor;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Normalizers\ArrayableNormalizer;
use Spatie\LaravelData\Normalizers\ArrayNormalizer;
use Spatie\LaravelData\Normalizers\JsonNormalizer;
use Spatie\LaravelData\Normalizers\ModelNormalizer;
use Spatie\LaravelData\Normalizers\ObjectNormalizer;


class AppointmentDetailDto extends Data
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

        #[MapInputName('AppointmentDate')]
        public ?string $appointmentDate,

        #[MapInputName('AppointmentTime')]
        public ?string $appointmentTime,

        #[MapInputName('AppointmentQue')]
        public ?string $appointmentQue,

        #[MapInputName('AppointmentNo')]
        public ?string $appointmentNo,

        #[MapInputName('PatientID')]
        public bool $patientID,

        #[MapInputName('FirstName')]
        public ?string $firstName,

        #[MapInputName('MiddleName')]
        public ?string $middleName,

        #[MapInputName('LastName')]
        public ?string $lastName,

        #[MapInputName('DateOfBirth')]
        public ?string $dateOfBirth,

        #[MapInputName('StreetName')]
        public ?string $streetName,

        #[MapInputName('District')]
        public ?string $district,

        #[MapInputName('City')]
        public ?string $city,

        #[MapInputName('County')]
        public ?string $county,

        #[MapInputName('State')]
        public ?string $state,

        #[MapInputName('ZipCode')]
        public ?string $zipCode,

        #[MapInputName('PhoneNo')]
        public ?string $phoneNo,

        #[MapInputName('Email')]
        public ?string $email,

        #[MapInputName('GuarantorID')]
        public ?string $guarantorID,

        #[MapInputName('GuarantorName')]
        public ?string $guarantorName,

        #[MapInputName('Notes')]
        public ?string $notes,

        #[MapInputName('AppointmentStatus')]
        public ?string $appointmentStatus,

        #[MapInputName('AppointmentStatusName')]
        public ?string $appointmentStatusName,

        #[MapInputName('MedicalNo')]
        public ?string $medicalNo,

        #[MapInputName('BirthPlace')]
        public ?string $birthPlace,

        #[MapInputName('Sex')]
        public ?string $sex,

        #[MapInputName('Ssn')]
        public ?string $ssn,

        #[MapInputName('MobilePhoneNo')]
        public ?string $mobilePhoneNo,

        #[MapInputName('AppointmentQueFormattedNo')]
        public ?string $appointmentQueFormattedNo,

        #[MapInputName('AppointmentDate_yMdHms')]
        public ?string $appointmentDate_yMdHms,

        #[MapInputName('DateOfBirth_yMdHms')]
        public ?string $dateOfBirth_yMdHms,
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
