<?php

namespace App\Tests\Connector;

use App\Connector\ProductConnectorSimulatedDb;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\HttpKernel\KernelInterface;

class ProductConnectorSimulatedDbTest extends TestCase
{
    private string $fixturesDir;

    protected function setUp(): void
    {
        $this->fixturesDir = __DIR__ . '/fixtures';
        if (!is_dir($this->fixturesDir)) {
            mkdir($this->fixturesDir, 0777, true);
        }
    }

    protected function tearDown(): void
    {
        array_map('unlink', glob($this->fixturesDir . '/*'));
        rmdir($this->fixturesDir);
    }

    public function testFetchDataWithValidFile(): void
    {
        $kernelMock = $this->createMock(KernelInterface::class);
        $projectDir = dirname($this->fixturesDir);
        $kernelMock->method('getProjectDir')->willReturn($projectDir);
        $fixtureDirPath = $projectDir . '/src/Connector';
        $fixtureFilePath = $fixtureDirPath . '/SimulatedDb.csv';

        if (!is_dir($fixtureDirPath)) {
            mkdir($fixtureDirPath, 0777, true);
        }

        file_put_contents($fixtureFilePath, <<<CSV
id;name;manufacturer;additional;price;product_image
1;Test Product;Test Manufacturer;Extra Info;100.00;https://example.com/image.jpg
CSV
        );

        $connector = new ProductConnectorSimulatedDb($kernelMock);
        $result = $connector->fetchData();

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('Test Product', $result[0]['name']);
        $this->assertEquals('Test Manufacturer', $result[0]['manufacturer']);
        $this->assertEquals('Extra Info', $result[0]['additional']);
        $this->assertEquals('100.00', $result[0]['price']);
        $this->assertEquals('https://example.com/image.jpg', $result[0]['product_image']);

        unlink($fixtureFilePath);
        rmdir($fixtureDirPath);
    }

    public function testFetchDataWithMissingFile(): void
    {
        $kernelMock = $this->createMock(KernelInterface::class);
        $kernelMock->method('getProjectDir')->willReturn($this->fixturesDir);
        $fixtureFilePath = $this->fixturesDir . '/SimulatedDb.csv';
        if (file_exists($fixtureFilePath)) {
            unlink($fixtureFilePath);
        }
        $connector = new ProductConnectorSimulatedDb($kernelMock);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('CSV file not found or not readable');

        $connector->fetchData();
    }

    public function testFetchDataWithEmptyFile(): void
    {
        $kernelMock = $this->createMock(KernelInterface::class);
        $projectDir = dirname($this->fixturesDir);
        $kernelMock->method('getProjectDir')->willReturn($projectDir);
        $fixtureDirPath = $projectDir . '/src/Connector';
        $fixtureFilePath = $fixtureDirPath . '/SimulatedDb.csv';

        if (!is_dir($fixtureDirPath)) {
            mkdir($fixtureDirPath, 0777, true);
        }

        file_put_contents($fixtureFilePath, '');

        $connector = new ProductConnectorSimulatedDb($kernelMock);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('CSV file is empty');

        $connector->fetchData();

        unlink($fixtureFilePath);
        rmdir($fixtureDirPath);
    }


    public function testFetchDataWithMalformedRows(): void
    {
        $kernelMock = $this->createMock(KernelInterface::class);
        $projectDir = dirname($this->fixturesDir);
        $kernelMock->method('getProjectDir')->willReturn($projectDir);
        $fixtureDirPath = $projectDir . '/src/Connector';
        $fixtureFilePath = $fixtureDirPath . '/SimulatedDb.csv';

        if (!is_dir($fixtureDirPath)) {
            mkdir($fixtureDirPath, 0777, true);
        }

        file_put_contents($fixtureFilePath, <<<CSV
id;name;manufacturer;additional;price;product_image
1;Valid Product;Manufacturer;Info;50.00;https://example.com/image1.jpg
2;Malformed Row
CSV
        );

        $connector = new ProductConnectorSimulatedDb($kernelMock);

        $result = $connector->fetchData();

        $this->assertIsArray($result);
        $this->assertCount(1, $result); // Only one valid row should be processed
        $this->assertEquals('Valid Product', $result[0]['name']);
        $this->assertEquals('Manufacturer', $result[0]['manufacturer']);
        $this->assertEquals('Info', $result[0]['additional']);
        $this->assertEquals('50.00', $result[0]['price']);
        $this->assertEquals('https://example.com/image1.jpg', $result[0]['product_image']);

        unlink($fixtureFilePath);
        rmdir($fixtureDirPath);
    }

}
