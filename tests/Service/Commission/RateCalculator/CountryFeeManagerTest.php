<?php

declare(strict_types=1);

namespace App\Tests\Service\Commission\RateCalculator;

use App\Service\Commission\RateCalculator\CountryFeeManager;
use PHPUnit\Framework\TestCase;

class CountryFeeManagerTest extends TestCase
{

    public function testGetFeeForCountry()
    {
        // arrange
        $euroCountry = 'UA';
        $noneuroCountry = 'US';
        $countryList = [$euroCountry];
        $euroFee = '0.05';
        $nonEuroFee = '0.10';
        $manager = new CountryFeeManager($countryList, $euroFee, $nonEuroFee);

        // act
        $resultEuro = $manager->getFeeForCountry($euroCountry);
        $resultNonEuro = $manager->getFeeForCountry($noneuroCountry);

        // assert
        self::assertSame($euroFee, $resultEuro);
        self::assertSame($resultNonEuro, $nonEuroFee);
    }
}
