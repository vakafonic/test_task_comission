<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class GuzzleMockTestCase extends TestCase
{
    protected static $requests = [];

    public function tearDown(): void
    {
        parent::tearDown();
        static::$requests = [];
    }

    /**
     * @param Response[]|Promise[] $responses
     * @return ClientInterface
     */
    protected function createClient(array $responses): ClientInterface
    {
        $mock = new MockHandler($responses);
        $history = Middleware::history(static::$requests);
        $stack = HandlerStack::create($mock);
        $stack->push($history);
        return new Client(['handler' => $stack]);
    }
}