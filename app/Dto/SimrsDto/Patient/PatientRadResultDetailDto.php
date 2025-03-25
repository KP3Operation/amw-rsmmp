<?php

namespace App\Dto\SimrsDto\Patient;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Normalizers\ArrayableNormalizer;
use Spatie\LaravelData\Normalizers\ArrayNormalizer;
use Spatie\LaravelData\Normalizers\JsonNormalizer;
use Spatie\LaravelData\Normalizers\ModelNormalizer;
use Spatie\LaravelData\Normalizers\ObjectNormalizer;

class PatientRadResultDetailDto extends Data
{
    public function __construct(

        // #[MapInputName('SuperDisplaySequence')]
        // public ?string $superDisplaySequence,

        #[MapInputName('TransactionNo')]
        public ?string $transactionNo,

        #[MapInputName('RegistrationNo')]
        public ?string $registrationNo,

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

        
        // #[MapInputName('SequenceNo')]
        // public ?string $sequenceNo,


        // #[MapInputName('LEVEL')]
        // public ?string $level,

        // #[MapInputName('ParentNo')]
        // public ?string $parentNo,

        // #[MapInputName('TestName')]
        // public ?string $testName,

        // #[MapInputName('Age')]
        // public ?string $age,

        // #[MapInputName('AgeType')]
        // public ?string $ageType,

        // #[MapInputName('Sex')]
        // public ?string $sex,

        // #[MapInputName('IsCito')]
        // public ?string $isCito,

        // #[MapInputName('ResultValue')]
        // public ?string $resultValue,

        // #[MapInputName('ItemUnit')]
        // public ?string $itemUnit,

        // #[MapInputName('IsDuplo')]
        // public ?string $isDuplo,

        // #[MapInputName('NormalValueMin')]
        // public ?string $normalValueMin,

        // #[MapInputName('NormalValueMax')]
        // public ?string $normalValueMax,

        // #[MapInputName('IsResultInput')]
        // public ?string $isResultInput,

        // #[MapInputName('ItemGroupName')]
        // public ?string $itemGroupName,

        // #[MapInputName('Notes')]
        // public ?string $notes,

        // #[MapInputName('IsDescriptionResult')]
        // public ?string $isDescriptionResult,

        // #[MapInputName('LV1')]
        // public ?string $lv1,

        // #[MapInputName('LV2')]
        // public ?string $lv2,

        // #[MapInputName('LV3')]
        // public ?string $lv3,

        // #[MapInputName('LV4')]
        // public ?string $lv4,

        // #[MapInputName('DisplaySequence')]
        // public ?string $displaySequence
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
