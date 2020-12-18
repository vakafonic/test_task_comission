<?php

declare(strict_types=1);

namespace App\Service\NumberPrinter\Conditions;

class NumberDividesCondition implements PrinterConditionInterface
{
    private int $divideBy;

    public function __construct(int $divideBy)
    {
        $this->divideBy = $divideBy;
    }

    public function match(int $number): bool
    {
        return $number % $this->divideBy === 0;

    }
}