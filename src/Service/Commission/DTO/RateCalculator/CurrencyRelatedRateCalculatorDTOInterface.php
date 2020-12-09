<?php

declare(strict_types=1);

namespace App\Service\Commission\DTO\RateCalculator;

interface CurrencyRelatedRateCalculatorDTOInterface extends RateCalculatorDTOInterface
{
    public function getCurrency(): string;
}