<?php

declare(strict_types=1);

namespace App\Service\Commission\Factory;

use App\Service\Commission\DTO\RateCalculator\CountryRelatedRateCalculatorDTO;
use App\Service\Commission\DTO\RateCalculator\CountryRelatedRateCalculatorDTOInterface;
use App\Service\Commission\DTO\RateCalculator\CurrencyRelatedRateCalculatorDTOInterface;
use App\Service\Commission\DTO\RateCalculator\RateCalculatorDTOInterface;
use App\Service\Commission\RateCalculator\EuropeanRelatedRateCalculator;
use InvalidArgumentException;

class RateCalculatorDTOFactory
{

    /**
     * @param string $calculatorClass
     * @param string $amount
     * @param string $currencyRate
     * @param string|null $currency
     * @param string|null $country
     * @return CountryRelatedRateCalculatorDTOInterface|CurrencyRelatedRateCalculatorDTOInterface|RateCalculatorDTOInterface
     */
    public function make(
        string $calculatorClass,
        string $amount,
        string $currencyRate,
        ?string $currency = null,
        ?string $country = null,
    ): RateCalculatorDTOInterface|CurrencyRelatedRateCalculatorDTOInterface|CountryRelatedRateCalculatorDTOInterface {
        return match ($calculatorClass) {
            EuropeanRelatedRateCalculator::class => new CountryRelatedRateCalculatorDTO(
                $amount,
                $currencyRate,
                $currency,
                $country
            ),
            default => throw new InvalidArgumentException('DTO is not configured for class ' . $calculatorClass)
        };
    }
}