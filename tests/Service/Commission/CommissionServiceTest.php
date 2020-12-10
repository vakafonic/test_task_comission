<?php

declare(strict_types=1);

namespace App\Tests\Service\Commission;

use App\Service\Commission\CommissionManager;
use App\Service\Commission\Exception\CalculationException;
use App\Service\Commission\Exception\DataReadException;
use App\Service\Commission\OldCommissionManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Kernel;

use function round;

/**
 * This functional test will show that new code works as old one
 * This test perform real call to API`s, too many requests is not processed, be aware
 *
 *
 * Class CommissionServiceTest
 * @package App\Tests\Service\Commission
 */
class CommissionServiceTest extends KernelTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
    }

    public static function getKernelClass(): string
    {
        return Kernel::class;
    }

    public function inputDataProvider(): array
    {
        return [
            'taskInput' => ['tests/data/input.txt']
        ];
    }

    /**
     * @dataProvider inputDataProvider
     * @param string $filePath
     * @throws CalculationException
     * @throws DataReadException
     */
    public function testThatAfterRefactorFunctionalityWorks(string $filePath): void
    {
        // arrange
        $oldService = new OldCommissionManager();
        $newService = static::$container->get(CommissionManager::class);
        $configuredPrecision = static::$container->getParameter('calculation_round');

        // act
        $outputOld = $oldService->calculateFromFile($filePath);
        $outputNew = $newService->calculateFromFile($filePath);
        // simulating same rounded values with old code
        $roundedOldValues = [];
        foreach ($outputOld as $key =>  $item) {
            $roundedOldValues[$key] = round($item, $configuredPrecision);
        }

        // assert
        self::assertEquals(
            $roundedOldValues,
            $outputNew,
            'Old and new service output is different'
        );
    }
}