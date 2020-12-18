<?php

declare(strict_types=1);

namespace App\Service\NumberPrinter\Middleware;

use Generator;

interface MiddlewareInterface
{
    public function processGeneratorValue(Generator $numberGenerator);
}