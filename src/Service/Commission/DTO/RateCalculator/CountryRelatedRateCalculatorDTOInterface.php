<?php

declare(strict_types=1);

namespace App\Service\Commission\DTO\RateCalculator;

interface CountryRelatedRateCalculatorDTOInterface extends CurrencyRelatedRateCalculatorDTOInterface
{
    public function getCountry(): string;
}