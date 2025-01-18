<?php

namespace App\Tests\Service;

use App\Connector\ProductConnector;
use App\Connector\ProductConnectorCollector;
use App\Service\ProductNormalizer;
use App\Service\ProductPipeline;
use ArrayIterator;
use PHPUnit\Framework\TestCase;

class ProductPipelineTest extends TestCase
{

    public function testProcessWithValidConnectors(): void
    {
        $connectorMock = $this->createMock(ProductConnector::class);
        $connectorMock->method('getName')->willReturn('SimulatedDb');
        $connectorMock->method('fetchData')->willReturn([
            [
                'id' => '1',
                'name' => 'Test Product',
                'manufacturer' => 'Test Manufacturer',
                'additional' => 'Extra Info',
                'product_image' => 'https://example.com/image.jpg',
                'price' => '100.00',
            ],
        ]);

        $collectorMock = $this->createMock(ProductConnectorCollector::class);
        $collectorMock->method('getIterator')->willReturn(new ArrayIterator([$connectorMock]));

        $normalizerMock = $this->createMock(ProductNormalizer::class);
        $normalizerMock->expects($this->once())
            ->method('setTemplate')
            ->with([
                'id' => ['id'],
                'title' => ['name'],
                'description' => ['manufacturer', 'additional'],
                'image_link' => ['product_image'],
                'price' => ['price'],
            ]);

        $normalizerMock->expects($this->once())
            ->method('normalize')
            ->with([
                [
                    'id' => '1',
                    'name' => 'Test Product',
                    'manufacturer' => 'Test Manufacturer',
                    'additional' => 'Extra Info',
                    'product_image' => 'https://example.com/image.jpg',
                    'price' => '100.00',
                ],
            ])
            ->willReturn([
                [
                    'id' => '1',
                    'title' => 'Test Product',
                    'description' => 'Test Manufacturer Extra Info',
                    'image_link' => 'https://example.com/image.jpg',
                    'price' => '100.00',
                ],
            ]);

        $pipeline = new ProductPipeline($collectorMock, $normalizerMock);
        $result = $pipeline->process();

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('Test Product', $result[0]['title']);
        $this->assertEquals('Test Manufacturer Extra Info', $result[0]['description']);
        $this->assertEquals('https://example.com/image.jpg', $result[0]['image_link']);
        $this->assertEquals('100.00', $result[0]['price']);
    }
}
