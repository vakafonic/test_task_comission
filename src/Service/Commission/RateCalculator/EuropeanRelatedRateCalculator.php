<?php

declare(strict_types=1);

namespace App\Service\Commission\RateCalculator;

use App\Service\Commission\DTO\RateCalculator\CountryRelatedRateCalculatorDTOInterface;

use function bcdiv;
use function bcmul;

class EuropeanRelatedRateCalculator implements RateCalculatorInterface
{
    /**
     * EuropeanRelatedRateCalculator constructor.
     * @param CountryFeeManagerInterface $countryFeeManager
     * @param string $baseCurrency ISO 4217 currency code
     * @param int $scale
     * @param int $precision
     */
    public function __construct(
        private CountryFeeManagerInterface $countryFeeManager,
        private string $baseCurrency,
        private int $scale,
        private int $precision
    ) {
    }

    public function getRate(
        CountryRelatedRateCalculatorDTOInterface $dto
    ): float {
        if ($dto->getCurrency() !== $this->baseCurrency) {
            $amount = bcdiv($dto->getAmount(), $dto->getCurrencyRate(), $this->scale);
        }

        $result = bcmul($amount ?? $dto->getAmount(), $this->getFeeForCountry($dto->getCountry()), $this->scale);
        return round((float)$result, $this->precision);
    }

    private function getFeeForCountry(
        string $country
    ): string {
        return $this->countryFeeManager->getFeeForCountry($country);
    }
}