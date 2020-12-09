<?php

declare(strict_types=1);

namespace App\Service\Commission;

interface CommissionManagerInterface
{
    /**
     * @param string $filepath
     * @return float[]
     */
    public function calculateFromFile(string $filepath): array;
}