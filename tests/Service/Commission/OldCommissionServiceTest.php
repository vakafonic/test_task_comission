<?php

declare(strict_types=1);

namespace App\Tests\Service\Commission;

use App\Service\Commission\CommissionManager;
use App\Service\Commission\OldCommissionManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Kernel;

class OldCommissionServiceTest extends KernelTestCase
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

    public function inputDataProvider()
    {
        return [
            'taskInput' => ['tests/data/input.txt']
//            'taskInput' => ['tests/data/input-wrong-field.txt']
        ];
    }

    /**
     * @dataProvider inputDataProvider
     */
    public function testGeneralFlow(string $filePath)
    {
        $service = new OldCommissionManager();
        $output = $service->calculateFromFile($filePath);
        $output[] = '';
    }

    /**
     * @dataProvider inputDataProvider
     */
    public function testNewFlow(string $filePath)
    {
//        $serviceOld = new OldCommissionService();
//        $outputOld = $serviceOld->calculateFromFile($filePath);
        $serviceNew = static::$container->get(CommissionManager::class);
        $outputNew = $serviceNew->calculateFromFile($filePath);
    }
}