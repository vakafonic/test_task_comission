<?php

declare(strict_types=1);

namespace App\Service\Commission;

use App\Service\Commission\DTO\Transaction;
use App\Service\Commission\Exception\ApiException;
use App\Service\Commission\Exception\CalculationException;
use App\Service\Commission\Exception\DataReadException;
use App\Service\Commission\ExternalApi\CountryProvider\CountryProviderInterface;
use App\Service\Commission\ExternalApi\ExchangeRateProvider\ExchangeRateProviderInterface;
use App\Service\Commission\Factory\RateCalculatorDTOFactory;
use App\Service\Commission\Factory\ReaderFactory;
use App\Service\Commission\Factory\TransactionFactory;
use App\Service\Commission\RateCalculator\RateCalculatorInterface;
use App\Service\Commission\Reader\ReaderInterface;

class CommissionService implements CommissionServiceInterface
{
    public function __construct(
        private ReaderFactory $readerFactory,
        private TransactionFactory $transactionFactory,
        private CountryProviderInterface $countryProvider,
        private ExchangeRateProviderInterface $rateProvider,
        private RateCalculatorInterface $rateCalculator,
        private RateCalculatorDTOFactory $rateCalculatorDTOFactory,
        private int $batchSize
    ) {
    }

    /**
     * @param string $filepath
     * @return float[]
     * @throws DataReadException
     * @throws CalculationException
     */
    public function calculateFromFile(string $filepath): array
    {
        $reader = $this->readFromFile($filepath);
        $output = [];
        foreach ($this->transactionFactory->makeTransactionDTO($reader, $this->batchSize) as $batch) {
            /** @var Transaction[] $batch */
            $output = \array_merge($output, $this->processTransactions($batch));
        }

        return $output;
    }

    /**
     * @param string $filePath
     * @return ReaderInterface
     * @throws DataReadException
     */
    private function readFromFile(string $filePath): ReaderInterface
    {
        return $this->readerFactory->fileReader($filePath);
    }

    /**
     * @param Transaction[] $transactions
     * @return float[]
     * @throws CalculationException
     */
    private function processTransactions(array $transactions): array
    {
        try {
            $countries = $this->countryProvider->getCountryListForTransactions($transactions);
        } catch (ApiException $e) {
            throw new CalculationException('Error fetching transaction country', $e->getCode(), $e);
        }

        try {
            $rates = $this->rateProvider->getRates();
        } catch (ApiException $e) {
            throw new CalculationException('Error fetching rates values', $e->getCode(), $e);
        }

        $output = [];
        foreach ($transactions as $transaction) {
            $dto = $this->rateCalculatorDTOFactory->make(
                $this->rateCalculator::class,
                $transaction->getAmount(),
                (string)$rates[$transaction->getCurrency()],
                $transaction->getCurrency(),
                $countries[$transaction->getBin()],
            );

            $output[] = (float)$this->rateCalculator->getRate($dto);
        }

        return $output;
    }
}