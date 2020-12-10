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

class FileReader implements ReaderInterface
{
    /** @var int hard limit for not correct files */
    public const MAX_ROW_LENGTH = 4096;
    /** @var resource */
    private $file;

    /**
     * FileReader constructor.
     * @param string $filePath
     * @throws DataReadException
     */
    public function __construct(string $filePath)
    {
        $this->file = fopen($filePath, 'rb');
        if (!is_resource($this->file)) {
            try {
                $error = json_encode(implode(', ', error_get_last()), JSON_THROW_ON_ERROR);
            } catch (JsonException $e) {
                $error = 'Cant convert error to string, ' . $e->getMessage();
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
        while (!feof($this->file)) {
            yield rtrim(fgets($this->file, static::MAX_ROW_LENGTH));
        }
    }
}