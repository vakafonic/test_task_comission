<?php

declare(strict_types=1);

namespace App\Service\Commission\Factory;

use App\Service\Commission\Exception\DataReadException;
use App\Service\Commission\Reader\FileReader;
use App\Service\Commission\Reader\FileReaderWithPagination;
use App\Service\Commission\Reader\ReaderInterface;

class ReaderFactory
{
    /**
     * @param string $filePath
     * @param int|null $offset
     * @param int|null $limit
     * @return FileReader
     * @throws DataReadException
     */
    public function fileReader(string $filePath, int $offset = null, int $limit = null): ReaderInterface
    {
        if ($offset !== null || $limit !== null) {
            return new FileReaderWithPagination($filePath, $offset, $limit);
        }

        return new FileReader($filePath);
    }
}