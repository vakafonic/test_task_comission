<?php

declare(strict_types=1);

namespace App\Service\NumberPrinter;

use App\Service\NumberPrinter\Middleware\MiddlewareInterface;
use App\Service\NumberPrinter\StringCompiler\StringCompilerInterface;
use Generator;

class NumberPrintManager
{
    private MiddlewareInterface $middleware;
    private StringCompilerInterface $stringCompiler;

    public function __construct(
        MiddlewareInterface $middleware,
        StringCompilerInterface $stringCompiler
    )
    {
        $this->middleware = $middleware;
        $this->stringCompiler = $stringCompiler;
    }

    /**
     * @param int[] $numbers
     * @return string
     */
    public function process(array $numbers): string
    {
        $numberGenerator = $this->createGeneratorFromArray($numbers);
        $processedStringGenerator = $this->middleware->processGeneratorValue($numberGenerator);
        return $this->stringCompiler->mergeToString($processedStringGenerator);
    }

    private function createGeneratorFromArray(array $numbers): Generator
    {
        yield from $numbers;
    }
}