<?php

namespace App\Dto\SimrsDto\Doctor;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class DoctorDto extends Data
{
    public function __construct(
        #[MapInputName('ParamedicID')]
        public string $paramedicId,

        #[MapInputName('ParamedicName')]
        public string $paramedicName,

        #[MapInputName('SmfID')]
        public string $smfId,

        #[MapInputName('SmfName')]
        public string $smfName,

        #[MapInputName('UserID')]
        public string|null $userId,

        #[MapInputName('Foto64')]
        public string|null $photo64
    )
    {}
}
