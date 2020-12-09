<?php

declare(strict_types=1);

namespace App\Service\Commission\RateCalculator;

interface CountryFeeManagerInterface
{
    /**
     * @param string $country
     * @return string float value of fee as string
     */
    public function getFeeForCountry(string $country): string;
}