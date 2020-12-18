<?php

declare(strict_types=1);

namespace App\Service\NumberPrinter\StringCompiler;

use Generator;

use function rtrim;

class SeparatorCompiler implements StringCompilerInterface
{
    private string $separator;

    public function __construct(string $separator)
    {
        $this->separator = $separator;
    }

    public function mergeToString(Generator $stringsGenerator): string
    {
        $output = '';
        foreach ($stringsGenerator as $string) {
            $output .=  $string . $this->separator;
        }

        return rtrim($output, $this->separator);
    }
}