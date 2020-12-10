<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Commission\ExternalApi\ExchangeRateProvider;

use App\Service\Commission\ExternalApi\ExchangeRateProvider\ExchangeRatesProvider;
use App\Tests\Helpers\GuzzleMockTestCase;
use GuzzleHttp\Psr7\Response;

class ExchangeRatesProviderTest extends GuzzleMockTestCase
{
    public function testGetRates()
    {
        // arrange
        $response = file_get_contents('tests/data/exchangerate.json');
        $client = $this->createClient([new Response(200, [], $response)]);
        $provider = new ExchangeRatesProvider($client, 'EUR');

        // act
        $result = $provider->getRates();

        // assert
        self::assertSame(
            'latest',
            (string)static::$requests[0]['request']->getUri(),
            'Incorrect uri for request'
        );

        self::assertArrayHasKey(
            'EUR',
            $result,
            'Rates response does not contain EUR rate'
        );

        self::assertEquals(
            1.0,
            $result['EUR'],
            'For basic currency rates should be always 1'
        );

        self::assertArrayHasKey(
            'JPY',
            $result,
            'Rates response does not contain JPY rate'
        );

        self::assertEquals(
            126.05,
            $result['JPY'],
            'For basic currency rates should be always 1'
        );
    }
}
