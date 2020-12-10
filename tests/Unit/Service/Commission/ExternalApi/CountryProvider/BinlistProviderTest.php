<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Commission\ExternalApi\CountryProvider;

use App\Service\Commission\DTO\Transaction;
use App\Service\Commission\ExternalApi\CountryProvider\BinlistProvider;
use App\Tests\Helpers\GuzzleMockTestCase;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Psr7\Response;
use JetBrains\PhpStorm\ArrayShape;
use Psr\Log\LoggerInterface;

use function json_decode;

class BinlistProviderTest extends GuzzleMockTestCase
{

    #[ArrayShape(['basic' => "\App\Service\Commission\DTO\Transaction[]"])]
    public function transactionDataProvider()
    {
        return [
            'basic' => [new Transaction('45717360', '100.00', 'EUR')]
        ];
    }

    /**
     * @dataProvider transactionDataProvider
     * @param Transaction $transaction
     */
    public function testCanReturnCorrectValues(Transaction $transaction)
    {
        // arrange
        $response = file_get_contents('tests/data/binlist.json');
        $promise = new Promise(
            static function () use (&$promise, $response)
            {
                $promise->resolve(new Response(200, [], $response));
            }
        );
        $client = $this->createClient([$promise]);
        $provider = new BinlistProvider($client, $this->createMock(LoggerInterface::class));

        // act
        $result = $provider->getCountryListForTransactions([$transaction]);

        // assert
        self::assertSame(
            $transaction->getBin(),
            (string)static::$requests[0]['request']->getUri(),
            'Incorrect uri for request'
        );

        self::assertArrayHasKey(
            $transaction->getBin(),
            $result,
            'Transaction response does not contain BIN key'
        );

        self::assertArrayHasKey(
            $transaction->getBin(),
            $result,
            'Transaction response does not contain BIN key'
        );

        self::assertEquals(
            $result[$transaction->getBin()],
            json_decode($response, true, 512, JSON_THROW_ON_ERROR)['country']['alpha2'],
            'Transaction response does not match mocked request'
        );
    }
}
