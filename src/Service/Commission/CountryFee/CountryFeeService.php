<?php

declare(strict_types=1);

namespace App\Service\Commission\CountryFee;

use JetBrains\PhpStorm\Pure;

use function in_array;

class CountryFeeService
{
    public function __construct(
        private array $europeanCountries,
        private string $europeanCountryFee,
        private string $nonEuropeanCountryFee
    ) {
    }

    #[Pure]
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