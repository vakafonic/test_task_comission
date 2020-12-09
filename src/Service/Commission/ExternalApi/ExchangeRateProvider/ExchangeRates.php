<?php

declare(strict_types=1);

namespace App\Service\Commission\ExternalApi\ExchangeRateProvider;

use App\Service\Commission\Exception\ApiException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

class ExchangeRates implements ExchangeRateProviderInterface
{
    public const ENDPOINT_LATEST = 'latest';

    public function __construct(private ClientInterface $client)
    {
    }

    /**
     * @inheritDoc
     */
    public function getRates(): array
    {
        try {
            $response = $this->client->request('GET', static::ENDPOINT_LATEST);
            $body = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException $guzzleException) {
            throw new ApiException($guzzleException->getMessage(), $guzzleException->getCode(), $guzzleException);
        } catch (JsonException $jsonException) {
            throw new ApiException($jsonException->getMessage(), $jsonException->getCode(), $jsonException);
        }
        $rates = $body['rates'] ?? throw new ApiException('Cant fetch rates, data structure is changed');
        $rates['EUR'] = 1.0;
        return $rates;
    }
}