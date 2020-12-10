<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Commission\RateCalculator;

use App\Service\Commission\DTO\RateCalculator\CountryRelatedRateCalculatorDTO;
use App\Service\Commission\RateCalculator\CountryFeeManager;
use App\Service\Commission\RateCalculator\EuropeanRelatedRateCalculator;
use PHPUnit\Framework\TestCase;

class EuropeanRelatedRateCalculatorTest extends TestCase
{
    private const EU_FEE = '0.0223';
    private const NON_EU_FEE = '0.02111';
    private const BASE_CURRENCY = 'EUR';

    public function rateDAtaProvider(): array
    {
        return [
            'nonEu' => [
                new CountryRelatedRateCalculatorDTO(
                    '11.00',
                    '2.0',
                    'USD',
                    'US'
                ),
                false,
                2,
                0.12 // 11/2*NON_EU_FEE = 0.116105 and round till 2 is 0.12
            ],
            'eu' => [
                new CountryRelatedRateCalculatorDTO(
                    '23.12',
                    '0.0123',
                    'UAH',
                    'UA'
                ),
                true,
                2,
                41.92 // 23.12/0.0123*EU_FEE = 41.9167479 and round till 2 is 41.92
            ],
            'basicCurrency' => [
                new CountryRelatedRateCalculatorDTO(
                    '1001.00',
                    '20.0', // testing with this not correct value to see that base currency definition works
                    static::BASE_CURRENCY,
                    'US'
                ),
                true,
                2,
                22.32 // 100*EU_FEE = 22.3223 and round till 2 is 22.32
            ],
            'differentPrecision' => [
                new CountryRelatedRateCalculatorDTO(
                    '1001.00',
                    '20.0', // testing with this not correct value to see that base currency definition works
                    static::BASE_CURRENCY,
                    'US'
                ),
                true,
                3,
                22.322 // 100*EU_FEE = 22.3223 and round till 3 is 22.322
            ],
        ];
    }

    /**
     * @dataProvider rateDAtaProvider
     * @param CountryRelatedRateCalculatorDTO $dto
     * @param bool $isEu
     * @param int $precision
     * @param float $expectedValue
     */
    public function testGetRate(CountryRelatedRateCalculatorDTO $dto, bool $isEu, int $precision, float $expectedValue): void
    {
        // arrange
        $countryFeeManager = $this->createMock(CountryFeeManager::class);
        $countryFeeManager->method('getFeeForCountry')->willReturnCallback(
            static function () use ($isEu){
                return $isEu ? static::EU_FEE : static::NON_EU_FEE;
            }
        );
        $calculator = new EuropeanRelatedRateCalculator($countryFeeManager, static::BASE_CURRENCY, 5, $precision);

        // act
        $result = $calculator->getRate($dto);

        // assert
        self::assertSame($expectedValue, $result);
    }
}
