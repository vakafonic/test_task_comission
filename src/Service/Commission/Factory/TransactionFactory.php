<?php

declare(strict_types=1);

namespace App\Service\Commission\Factory;

use App\Service\Commission\DTO\Transaction;
use App\Service\Commission\Reader\ReaderInterface;
use Generator;
use Psr\Log\LoggerInterface;
use Throwable;

use function json_decode;
use function sprintf;

class TransactionFactory
{
    protected const BATCH_SIZE = 2;
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function makeTransactionDTO(ReaderInterface $reader, int $batchSize = self::BATCH_SIZE):  Generator
    {
        $dtoCollection = [];
        $batchCounter = 0;
        foreach ($reader->read() as $row) {
            try {
                $decodedRow = json_decode($row, flags: JSON_THROW_ON_ERROR);
            } catch (Throwable) {
                $this->logger->warning(
                    sprintf(
                        'String of input cant be decoded as json, data: %s',
                        $row
                    )
                );
                continue;
            }
            try {
                $dtoCollection[] = new Transaction($decodedRow->bin, $decodedRow->amount, $decodedRow->currency);
                $batchCounter++;
            } catch (Throwable $exception) {
                $this->logger->warning(
                    sprintf(
                        'Not correct json body, missing parameter or wrong type: %s, row was: %s',
                        $exception,
                        $row
                    )
                );
            }

            if ($batchCounter === $batchSize) {
                yield $dtoCollection;
                $dtoCollection = [];
                $batchCounter = 0;
            }
        }

        if (!empty($dtoCollection)) {
            yield $dtoCollection;
        }
    }
}