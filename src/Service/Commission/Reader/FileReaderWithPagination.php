<?php

declare(strict_types=1);

namespace App\Service\Commission\Reader;

use App\Service\Commission\Exception\DataReadException;
use Generator;
use JsonException;

use function fclose;
use function fgets;
use function fopen;
use function implode;
use function is_resource;
use function json_encode;
use function rtrim;

class FileReaderWithPagination implements ReaderInterface
{
    /** @var int hard limit for not correct files */
    public const MAX_ROW_LENGTH = 4096;
    public const DEFAULT_OFFSET = 0;
    public const DEFAULT_ROW_LIMIT = 1;
    /** @var resource */
    private $file;
    private int $rowOffset;
    private int $rowLimit;

    /**
     * FileReader constructor.
     * @param string $filePath
     * @param int $rowOffset
     * @param int $rowLimit
     * @throws DataReadException
     */
    public function __construct(
        string $filePath,
        int $rowOffset = self::DEFAULT_OFFSET,
        int $rowLimit = self::DEFAULT_ROW_LIMIT
    ) {
        $this->rowOffset = $rowOffset;
        $this->rowLimit = $rowLimit;
        $this->file = fopen($filePath, 'rb');
        if (!is_resource($this->file)) {
            try {
                $error = error_get_last();
                $error = json_encode(implode(', ', $error), JSON_THROW_ON_ERROR);
            } catch (JsonException $e) {
                $error = 'cant convert error to string, ' . $e->getMessage();
            }
            throw new DataReadException('File read exception: ' . $error);
        }
    }

    public function __destruct()
    {
        fclose($this->file);
    }

    public function read(): Generator
    {
        if ($this->rowOffset !== self::DEFAULT_OFFSET) {
            for ($rowsCounter = 1; fgets($this->file, static::MAX_ROW_LENGTH) !== false; $rowsCounter++) {
                if ($rowsCounter === $this->rowOffset) {
                    break;
                }
            }
        }
        $rowsCounter = 0;
        while (!feof($this->file)) {
            yield rtrim(fgets($this->file, static::MAX_ROW_LENGTH));
            $rowsCounter++;
            if ($this->rowLimit === $rowsCounter) {
                break;
            }
        }
    }
}