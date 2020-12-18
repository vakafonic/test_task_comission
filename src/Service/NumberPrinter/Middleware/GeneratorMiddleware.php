<?php

declare(strict_types=1);

namespace App\Service\NumberPrinter\Middleware;

use App\Service\NumberPrinter\Printer\PrinterInterface;
use Generator;

class GeneratorMiddleware implements MiddlewareInterface
{
    private PrinterInterface $printer;

    public function __construct(
        PrinterInterface $printer
    )
    {
        $this->printer = $printer;
    }

    public function processGeneratorValue(Generator $numberGenerator): Generator
    {
        foreach ($numberGenerator as $number) {
            yield $this->printer->getOutput($number);
        }
    }
}