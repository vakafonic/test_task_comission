<?php

declare(strict_types=1);

namespace App\Service\Commission\DTO;

class Transaction
{
    public function __construct(
        private string $bin,
        private string $amount,
        private string $currency
    ) {
    }

    public function getBin(): string
    {
        return $this->bin;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}