<?php

declare(strict_types=1);

namespace App\Service\NumberPrinter\Conditions;

use function in_array;

class OneOfCondition implements PrinterConditionInterface
{
    private array $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function match(int $number): bool
    {
        return in_array($number, $this->values, true);
    }
}