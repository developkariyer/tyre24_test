<?php

namespace App\Tests\Service;

use App\Service\ProductNormalizer;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ProductNormalizerTest extends TestCase
{
    private array $validTemplate = [
        'id' => ['id'],
        'title' => ['name'],
        'description' => ['manufacturer', 'additional'],
        'image_link' => ['product_image'],
        'price' => ['price'],
    ];

    public function testNormalizeWithValidTemplate(): void
    {
        $normalizer = new ProductNormalizer();
        $normalizer->setTemplate($this->validTemplate);

        $rawData = [
            [
                'id' => '1',
                'name' => 'Test Product',
                'manufacturer' => 'Test Manufacturer',
                'additional' => 'Extra Info',
                'product_image' => 'https://example.com/image.jpg',
                'price' => '10.00',
            ],
        ];

        $expected = [
            [
                'id' => '1',
                'title' => 'Test Product',
                'description' => 'Test Manufacturer Extra Info',
                'image_link' => 'https://example.com/image.jpg',
                'price' => '10.00',
            ],
        ];

        $this->assertEquals($expected, $normalizer->normalize($rawData));
    }

    public function testNormalizeThrowsExceptionWhenTemplateNotSet(): void
    {
        $normalizer = new ProductNormalizer();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Template is not set');

        $normalizer->normalize([]);
    }

    public function testSetTemplateWithInvalidKey(): void
    {
        $normalizer = new ProductNormalizer();
        $invalidTemplate = [
            'invalidKey' => ['name'],
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Template key 'invalidKey' is not allowed");

        $normalizer->setTemplate($invalidTemplate);
    }

    public function testSetTemplateWithMissingRequiredKeys(): void
    {
        $normalizer = new ProductNormalizer();
        $incompleteTemplate = [
            'id' => ['id'],
            'title' => ['name'],
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Template is missing required key(s): description, image_link, price");

        $normalizer->setTemplate($incompleteTemplate);
    }

    public function testSetTemplateWithInvalidValue(): void
    {
        $normalizer = new ProductNormalizer();
        $invalidTemplate = [
            'id' => ['id'],
            'title' => ['name'],
            'description' => 'not_an_array',
            'image_link' => ['product_image'],
            'price' => ['price'],
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Template value for 'description' must be an array of strings");

        $normalizer->setTemplate($invalidTemplate);
    }

    public function testGetTemplate(): void
    {
        $normalizer = new ProductNormalizer();
        $normalizer->setTemplate($this->validTemplate);

        $this->assertEquals($this->validTemplate, $normalizer->getTemplate());
    }

    public function testNormalizeSkipsMissingFields(): void
    {
        $normalizer = new ProductNormalizer();
        $normalizer->setTemplate($this->validTemplate);

        $rawData = [
            [
                'id' => '1',
                'name' => 'Test Product',
                'manufacturer' => 'Test Manufacturer',
                'price' => '10.00',
            ],
        ];

        $expected = [
            [
                'id' => '1',
                'title' => 'Test Product',
                'description' => 'Test Manufacturer',
                'image_link' => '',
                'price' => '10.00',
            ],
        ];

        $this->assertEquals($expected, $normalizer->normalize($rawData));
    }
}
