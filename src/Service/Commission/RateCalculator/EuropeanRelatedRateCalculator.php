<?php

declare(strict_types=1);

namespace App\Service\Commission\RateCalculator;

use App\Service\Commission\CountryFee\CountryFeeService;
use App\Service\Commission\DTO\RateCalculator\CountryRelatedRateCalculatorDTOInterface;

use function bcdiv;
use function bcmul;

class EuropeanRelatedRateCalculator implements RateCalculatorInterface
{

    public function __construct(private CountryFeeService $feeService, private string $baseCurrency, private int $scale)
    {
    }

    public function getRate(
        CountryRelatedRateCalculatorDTOInterface $dto
    ): string {
        if ($dto->getCurrency() !== $this->baseCurrency) {
            $amount = bcdiv($dto->getAmount(), $dto->getCurrencyRate(), $this->scale);
        }

        return bcmul($amount ?? $dto->getAmount(), $this->getFeeForCountry($dto->getCountry()), $this->scale);
    }

    private function getFeeForCountry(
        string $country
    ): string {
        return $this->feeService->getFeeForCountry($country);
    }
}