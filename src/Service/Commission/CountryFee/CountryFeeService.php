<?php

declare(strict_types=1);

namespace App\Service\Commission\CountryFee;

use function in_array;

class CountryFeeService
{
    public function __construct(
        private array $europeanCountries,
        private string $europeanCountryFee,
        private string $nonEuropeanCountryFee
    ) {
    }

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