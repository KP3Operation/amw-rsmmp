<?php

namespace App\Dto\SimrsDto\Doctor;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Normalizers\ArrayableNormalizer;
use Spatie\LaravelData\Normalizers\ArrayNormalizer;
use Spatie\LaravelData\Normalizers\JsonNormalizer;
use Spatie\LaravelData\Normalizers\ModelNormalizer;
use Spatie\LaravelData\Normalizers\ObjectNormalizer;

class DoctorFeeByPaymentDateTrxDataDto extends Data
{

    public function __construct(
        #[MapInputName('PaymentGroup')]
        public string $paymentGroup,

        #[MapInputName('PaymentDate')]
        public string $paymentDate,

        #[DataCollectionOf(DoctorFeeByPaymentDateDto::class)]
        #[MapInputName('Transaction')]
        public DataCollection $transaction
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
