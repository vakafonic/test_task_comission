<?php

declare(strict_types=1);


namespace App\Service\NumberPrinter\Conditions;

interface PrinterConditionInterface
{
    public function match(int $number):bool;
}