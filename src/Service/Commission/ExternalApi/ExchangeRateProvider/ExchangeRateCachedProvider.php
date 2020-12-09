<?php

declare(strict_types=1);

namespace App\Service\Commission\ExternalApi\ExchangeRateProvider;

use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ExchangeRateCachedProvider implements ExchangeRateProviderInterface
{
    /** @var string this should be moved to provider if more keys will be added */
    public const CACHE_KEY_RATES = 'rates';

    public function __construct(
        private ExchangeRateProviderInterface $realProvider,
        private CacheInterface $cache,
        private int $cacheTTL
    ) {
    }

    /**
     * @return string[]
     * @throws InvalidArgumentException
     */
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