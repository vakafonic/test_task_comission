<?php

declare(strict_types=1);

namespace App\Service\NumberPrinter\Printer;

use App\Service\NumberPrinter\Conditions\PrinterConditionInterface;

class ConditionPrinter implements  PrinterInterface
{
    private PrinterConditionInterface $conditionOne;
    private string $outputOne;
    private PrinterConditionInterface $conditionTwo;
    private string $outputTwo;

    public function __construct(
        PrinterConditionInterface $conditionOne,
        string $outputOne,
        PrinterConditionInterface $conditionTwo,
        string $outputTwo
    )
    {
        $this->conditionOne = $conditionOne;
        $this->outputOne = $outputOne;
        $this->conditionTwo = $conditionTwo;
        $this->outputTwo = $outputTwo;
    }


    public function getOutput(int $number): string
    {
        $conditionOneResult = $this->conditionOne->match($number);
        $conditionTwoResult = $this->conditionTwo->match($number);

        if ($conditionOneResult && $conditionTwoResult) {
            return $this->outputOne . $this->outputTwo;
        }

        if ($conditionOneResult) {
            return $this->outputOne;
        }

        if ($conditionTwoResult) {
            return $this->outputTwo;
        }

        return (string)$number;
    }
}