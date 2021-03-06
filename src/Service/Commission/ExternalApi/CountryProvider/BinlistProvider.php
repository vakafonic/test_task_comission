<?php

declare(strict_types=1);

namespace App\Service\Commission\ExternalApi\CountryProvider;

use App\Service\Commission\DTO\Transaction;
use App\Service\Commission\Exception\ApiException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\Utils;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;

use function json_decode;
use function sprintf;


class BinlistProvider implements CountryProviderInterface
{

    public function __construct(private ClientInterface $client, private LoggerInterface $logger)
    {
    }

    /**
     * @param Transaction[] $transactions
     * @return array country value indexed by key ['bin' => 'country']
     * @throws ApiException
     */
    public function getCountryListForTransactions(array $transactions): array
    {
        $promises = [];
        $map = [];
        foreach ($transactions as $transaction) {
            $promises[] = $this->client->requestAsync('GET', $transaction->getBin())->then(
                function (ResponseInterface $response) use ($transaction, &$map): void
                {
                    if ($response->getStatusCode() === 200) {
                        $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
                        $map[$transaction->getBin()] = $data['country']['alpha2'];
                    } else {
                        $this->logger->error(
                            sprintf('Transaction country fetch failed for bin %s', $transaction->getBin())
                        );
                    }
                }
            );
        }
        try {
            Utils::all($promises)->wait();
        } catch (Throwable $exception) { // Throwable because of a lot variety of things that could happened
            throw new ApiException($exception->getMessage(), $exception->getCode(), $exception);
        }

        return $map;
    }
}