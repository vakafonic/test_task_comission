<?php

declare(strict_types=1);

namespace App\Service\Commission\Reader;

use Generator;

interface ReaderInterface
{
    /**
     * @return Generator that will read input one by one and return json string
     */
    public function read(): Generator;
}