<?php

declare(strict_types=1);

namespace App\Service\Commission\DTO\RateCalculator;

class CountryRelatedRateCalculatorDTO extends CurrencyRelatedRateCalculatorDTO
    implements CountryRelatedRateCalculatorDTOInterface
{
    public function __construct(
        string $amount,
        string $currencyRate,
        string $currency,
        protected string $country,
    ) {
        parent::__construct($amount, $currencyRate, $currency);
    }

    public function getCountry(): string
    {
        return $this->country;
    }
}