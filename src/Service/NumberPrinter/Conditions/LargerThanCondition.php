<?php

declare(strict_types=1);

namespace App\Service\NumberPrinter\Conditions;

use function in_array;

class LargerThanCondition implements PrinterConditionInterface
{
    private int $compareTo;

    public function __construct(int $compareTo)
    {
        $this->compareTo = $compareTo;
    }

    public function match(int $number): bool
    {
        return $number > $this->compareTo;
    }
}