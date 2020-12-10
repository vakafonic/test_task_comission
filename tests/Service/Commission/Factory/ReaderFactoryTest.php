<?php

declare(strict_types=1);

namespace App\Tests\Service\Commission\Factory;

use App\Service\Commission\Factory\ReaderFactory;
use PHPUnit\Framework\TestCase;

class ReaderFactoryTest extends TestCase
{

    public function testFileReader(): void
    {
        // arrange
        $filePath  = 'tests/data/input.txt';
        $factory = new ReaderFactory();

        // act
        $fileReaderBasic = $factory->fileReader($filePath);
        foreach ($fileReaderBasic->read() as $row) {
            $fileReaderBasicRows[] = $row;
        }

        // assert
        self::assertCount(5, $fileReaderBasicRows);
        self::assertEquals('{"bin":"45717360","amount":"100.00","currency":"EUR"}', $fileReaderBasicRows[0]);
        self::assertEquals('{"bin":"4745030","amount":"2000.00","currency":"GBP"}', $fileReaderBasicRows[4]);
    }

    public function testFileReaderWithPagination(): void
    {
        // arrange
        $filePath  = 'tests/data/input.txt';
        $factory = new ReaderFactory();

        // act
        $fileReaderPagination = $factory->fileReader($filePath, 2, 2);
        foreach ($fileReaderPagination->read() as $row) {
            $fileReaderPaginationRows[] = $row;
        }

        // assert
        self::assertCount(2, $fileReaderPaginationRows);
        self::assertEquals('{"bin":"45417360","amount":"10000.00","currency":"JPY"}', $fileReaderPaginationRows[0]);
        self::assertEquals('{"bin":"41417360","amount":"130.00","currency":"USD"}', $fileReaderPaginationRows[1]);

    }
}
