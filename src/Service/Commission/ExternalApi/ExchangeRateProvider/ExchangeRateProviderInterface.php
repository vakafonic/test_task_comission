<?php

declare(strict_types=1);

namespace App\Service\Commission\ExternalApi\ExchangeRateProvider;

use App\Service\Commission\Exception\ApiException;

interface ExchangeRateProviderInterface
{
    /**
     * Returns an associative array of ISO 4217 currency signs linked to float currency rate
     * The comparison will be made for default currency (EUR)
     *
     * @return string[] mapped currency to rate like ['USD' => 0.23, 'UAH' => 2.45]
     * @throws ApiException
     */
    public function getRates(): array;
}