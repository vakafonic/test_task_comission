<?php

declare(strict_types=1);

namespace App\Service\Commission\RateCalculator;

use function in_array;

class CountryFeeManager implements CountryFeeManagerInterface
{
    public function __construct(
        private array $europeanCountries,
        private string $europeanCountryFee,
        private string $nonEuropeanCountryFee
    ) {
    }

    /**
     * @param string $country
     * @return string float value of fee as string
     */
    public function getFeeForCountry(
        string $country
    ): string {
        return in_array(
            $country,
            $this->europeanCountries,
            true
        ) ? $this->europeanCountryFee : $this->nonEuropeanCountryFee;
    }
}