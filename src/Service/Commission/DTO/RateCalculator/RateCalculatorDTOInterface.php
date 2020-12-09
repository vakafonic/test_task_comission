<?php

declare(strict_types=1);


namespace App\Service\Commission\DTO\RateCalculator;


interface RateCalculatorDTOInterface
{
    public function getAmount(): string;

    public function getCurrencyRate(): string;

}