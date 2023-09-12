<?php

namespace App\Dto\SimrsDto;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Normalizers\ArrayableNormalizer;
use Spatie\LaravelData\Normalizers\ArrayNormalizer;
use Spatie\LaravelData\Normalizers\JsonNormalizer;
use Spatie\LaravelData\Normalizers\ModelNormalizer;
use Spatie\LaravelData\Normalizers\ObjectNormalizer;

class PatientVitalSignHistoryDto  extends Data
{
    public function __construct(
        #[MapInputName('QuestionAnswerText')]
        public string $questionAnswerText,
        #[MapInputName('QuestionAnswerText2')]
        public string $questionAnswerText2,
        #[MapInputName('RecordDate')]
        public string $recordDate,
        #[MapInputName('RecordTime')]
        public string $recordTime,
        #[MapInputName('QuestionAnswerNum')]
        public string|null $questionAnswerNum,
        #[MapInputName('QuestionAnswerPrefix')]
        public string $questionAnswerPrefix,
        #[MapInputName('RegistrationNo')]
        public string $registrationNo,
        #[MapInputName('VitalSignID')]
        public string $vitalSignId,
        #[MapInputName('VitalSignName')]
        public string|null $vitalSignName,
        #[MapInputName('VitalSignUnit')]
        public string|null $vitalSignUnit,
        #[MapInputName('RecordDate_yMdHms')]
        public string $recordDate_yMdHms,
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
