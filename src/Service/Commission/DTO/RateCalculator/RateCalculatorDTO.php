<?php

declare(strict_types=1);

namespace App\Service\Commission\DTO\RateCalculator;

class RateCalculatorDTO implements RateCalculatorDTOInterface
{
    public function __construct(protected string $amount, protected string $currencyRate)
    {
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrencyRate(): string
    {
        return $this->currencyRate;
    }

}