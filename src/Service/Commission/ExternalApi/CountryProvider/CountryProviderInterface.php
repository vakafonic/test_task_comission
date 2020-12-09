<?php

declare(strict_types=1);

namespace App\Service\Commission\ExternalApi\CountryProvider;

use App\Service\Commission\DTO\Transaction;
use App\Service\Commission\Exception\ApiException;

interface CountryProviderInterface
{
    /**
     * @param Transaction[] $transactions
     * @return array country value indexed by key ['bin' => 'country']
     * @throws ApiException
     */
    public function getCountryListForTransactions(array $transactions): array;
}