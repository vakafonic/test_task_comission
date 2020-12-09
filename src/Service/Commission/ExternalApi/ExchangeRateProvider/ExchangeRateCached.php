<?php

declare(strict_types=1);

namespace App\Service\Commission\ExternalApi\ExchangeRateProvider;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ExchangeRateCached implements ExchangeRateProviderInterface
{
    public const CACHE_KEY_RATES = 'rates';

    public function __construct(
        private ExchangeRateProviderInterface $realProvider,
        private CacheInterface $cache,
        private int $cacheTTL
    ) {
    }

    public function getRates(): array
    {
        return $this->cache->get(
            static::CACHE_KEY_RATES,
            function (ItemInterface $item)
            {
                $item->expiresAfter($this->cacheTTL);
                return $this->realProvider->getRates();
            }
        );
    }
}