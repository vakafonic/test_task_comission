<?php

declare(strict_types=1);

namespace App\Service\Commission\RateCalculator;

use App\Service\Commission\DTO\RateCalculator\CountryRelatedRateCalculatorDTOInterface;

interface RateCalculatorInterface
{
    public function getRate(CountryRelatedRateCalculatorDTOInterface $dto): string;
}