<?php

namespace App\Dto\SimrsDto\Patient;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Normalizers\ArrayableNormalizer;
use Spatie\LaravelData\Normalizers\ArrayNormalizer;
use Spatie\LaravelData\Normalizers\JsonNormalizer;
use Spatie\LaravelData\Normalizers\ModelNormalizer;
use Spatie\LaravelData\Normalizers\ObjectNormalizer;

class PatientRadResultDto extends Data
{
    public function __construct(
        #[MapInputName('RegistrationNo')]
        public ?string $registrationNo,

        #[MapInputName('TransactionNo')]
        public ?string $transactionNo,

        #[MapInputName('ItemID')]
        public ?string $itemID,

        #[MapInputName('ItemName')]
        public ?string $itemName,

        #[MapInputName('TestResultDateTime_yMdHms')]
        public ?string $testResultDate,

        #[MapInputName('TestResult')]
        public ?string $testResult,

        #[MapInputName('TestSummary')]
        public ?string $testSummary,

        #[MapInputName('TestSuggest')]
        public ?string $testSuggest,
        
        #[MapInputName('ReadBy')]
        public ?string $readBy,
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
