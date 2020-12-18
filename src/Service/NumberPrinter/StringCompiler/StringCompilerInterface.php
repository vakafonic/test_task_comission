<?php

declare(strict_types=1);

namespace App\Service\NumberPrinter\StringCompiler;

use Generator;

interface StringCompilerInterface
{
    public function mergeToString(Generator $stringsGenerator): string;
}