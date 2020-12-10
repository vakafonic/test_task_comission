<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Commission\Factory;

use App\Service\Commission\DTO\RateCalculator\CountryRelatedRateCalculatorDTO;
use App\Service\Commission\DTO\RateCalculator\CountryRelatedRateCalculatorDTOInterface;
use App\Service\Commission\DTO\RateCalculator\CurrencyRelatedRateCalculatorDTOInterface;
use App\Service\Commission\Factory\RateCalculatorDTOFactory;
use App\Service\Commission\RateCalculator\EuropeanRelatedRateCalculator;
use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\TestCase;

class RateCalculatorDTOFactoryTest extends TestCase
{

    #[ArrayShape(['EuropeanRelated' => "string[]"])]
    public function currencyDataProvider()
    {
        return [
            'EuropeanRelated' => [
                EuropeanRelatedRateCalculator::class,
                CountryRelatedRateCalculatorDTO::class,
                '10.00',
                '1.3',
                'USD',
                'UA'
            ]
        ];
    }

    /**
     * @dataProvider currencyDataProvider
     * @param string $calculatorClass
     * @param string $expectedDtoClass
     * @param string $amount
     * @param string $currencyRate
     * @param string $currency
     * @param string $country
     */
    public function testMake(
        string $calculatorClass,
        string $expectedDtoClass,
        string $amount,
        string $currencyRate,
        string $currency,
        string $country,
    )
    {
        // arrange
        $factory = new RateCalculatorDTOFactory();

        // act
        $dto = $factory->make($calculatorClass, $amount, $currencyRate, $currency, $country);

        // assert
        self::assertEquals(
            $expectedDtoClass,
            $dto::class
        );
        self::assertEquals(
            $amount,
            $dto->getAmount()
        );
        self::assertEquals(
            $currencyRate,
            $dto->getCurrencyRate()
        );

        if ($dto instanceof CurrencyRelatedRateCalculatorDTOInterface) {
            self::assertEquals(
                $currency,
                $dto->getCurrency()
            );
        }

        if ($dto instanceof CountryRelatedRateCalculatorDTOInterface) {
            self::assertEquals(
                $country,
                $dto->getCountry()
            );
        }
    }
}
