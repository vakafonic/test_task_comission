<?php

declare(strict_types=1);

namespace App\Service\Commission\ExternalApi\ExchangeRateProvider;

use App\Service\Commission\Exception\ApiException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

class ExchangeRatesProvider implements ExchangeRateProviderInterface
{
    public const ENDPOINT_LATEST = 'latest';

    public function __construct(private ClientInterface $client, private string $baseCurrency)
    {
    }

    /**
     * @return string[]
     * @throws ApiException
     */
    public function getRates(): array
    {
        try {
            // Base currency EUR is used by default in request, add currency param to request if it will be changed
            $response = $this->client->request('GET', static::ENDPOINT_LATEST);
            $body = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException $guzzleException) {
            throw new ApiException($guzzleException->getMessage(), $guzzleException->getCode(), $guzzleException);
        } catch (JsonException $jsonException) {
            throw new ApiException($jsonException->getMessage(), $jsonException->getCode(), $jsonException);
        }

        if ($body['base'] !== $this->baseCurrency) {
            throw new ApiException('Api response changed default currency');
        }

        $rates = $body['rates'] ?? throw new ApiException('Cant fetch rates, data structure is changed');

        // Rate for base currency is not in response, so we add it manually
        $rates[$this->baseCurrency] = 1.0;

        return $rates;
    }
}