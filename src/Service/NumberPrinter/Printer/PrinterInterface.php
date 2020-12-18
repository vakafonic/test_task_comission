<?php

declare(strict_types=1);

namespace App\Service\NumberPrinter\Printer;

interface PrinterInterface
{
    /**
     * @param int $number
     * @return string
     */
    public function getOutput(int $number): string;
}