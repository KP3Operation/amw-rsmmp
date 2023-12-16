<?php

namespace App\Dto\SimrsDto\Doctor;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class AppointmentDto extends Data
{
    public function __construct(
        #[MapInputName('AppointmentNo')]
        public ?string $appointmentNo,

        #[MapInputName('AppointmentDate')]
        public ?string $appointmentDate,

        #[MapInputName('AppointmentTime')]
        public ?string $appointmentTime,

        #[MapInputName('AppointmentStatus')]
        public ?string $appointmentStatus,

        #[MapInputName('MedicalNo')]
        public ?string $medicalNo,

        #[MapInputName('PatientName')]
        public ?string $patientName,

        #[MapInputName('AppointmentQue')]
        public ?string $appointmentQue,

        #[MapInputName('GuarantorName')]
        public ?string $guarantorName,

        #[MapInputName('IsNewVisit')]
        public bool $isNewVisit,

        #[MapInputName('AppointmentDate_yMdHms')]
        public ?string $appointmentDate_yMdHms,
    ) {
    }
}
