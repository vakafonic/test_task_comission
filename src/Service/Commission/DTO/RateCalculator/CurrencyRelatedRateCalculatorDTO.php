<?php

declare(strict_types=1);

namespace App\Service\Commission\DTO\RateCalculator;

use JetBrains\PhpStorm\Pure;

class CurrencyRelatedRateCalculatorDTO extends RateCalculatorDTO
    implements CurrencyRelatedRateCalculatorDTOInterface
{
    #[Pure]
    public function __construct(
        string $amount,
        string $currencyRate,
        protected string $currency
    ) {
        parent::__construct($amount, $currencyRate);
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}