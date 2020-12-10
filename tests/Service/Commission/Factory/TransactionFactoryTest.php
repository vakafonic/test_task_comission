<?php

declare(strict_types=1);

namespace App\Tests\Service\Commission\Factory;

use App\Service\Commission\DTO\Transaction;
use App\Service\Commission\Factory\TransactionFactory;
use App\Service\Commission\Reader\ReaderInterface;
use Generator;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class TransactionFactoryTest extends TestCase
{

    public function testMakeTransactionDTO()
    {
        // arrange
        $factory = new TransactionFactory($this->createMock(LoggerInterface::class));
        $mockedReader = $this->createMock(ReaderInterface::class);
        $mockedReader->method('read')->willReturn($this->createReaderGenerator());

        // act
        $transactionsGenerator = $factory->makeTransactionDTO($mockedReader);
        $transactions = $transactionsGenerator->current();

        // assert
        self::assertInstanceOf(Transaction::class, $transactions[0]);
        self::assertSame('45417360', $transactions[0]->getBin());
        self::assertSame('10000.00', $transactions[0]->getAmount());
        self::assertSame('JPY', $transactions[0]->getCurrency());
        self::assertInstanceOf(Transaction::class, $transactions[1]);
        self::assertSame('41417360', $transactions[1]->getBin());
        self::assertSame('130.00', $transactions[1]->getAmount());
        self::assertSame('USD', $transactions[1]->getCurrency());
    }

    private function createReaderGenerator(): Generator
    {
        $data = [
            '{"bin":"45417360","amount":"10000.00","currency":"JPY"}',
            '{"bin":"41417360","amount":"130.00","currency":"USD"}',
        ];

        yield from $data;
    }
}
