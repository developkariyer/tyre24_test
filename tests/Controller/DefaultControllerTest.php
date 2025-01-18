<?php

namespace App\Tests\Controller;

use App\Service\ProductPipeline;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testMainPageDisplaysProducts(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // Ensure the response is successful
        $this->assertResponseIsSuccessful();

        // Check the page title
        $this->assertSelectorTextContains('title', 'Main page!');

        // Check the page heading
        $this->assertSelectorTextContains('h1', 'Product List');

        // Check if product cards are displayed
        $this->assertSelectorExists('.card');

        // Validate the number of product cards displayed (if expected number is known, e.g., 6)
        $this->assertGreaterThan(0, $crawler->filter('.card')->count());
    }

    public function testMainPageNoProductsMessage(): void
    {
        $client = static::createClient();

        // Create a mock ProductPipeline to simulate no products
        $mockPipeline = $this->getMockBuilder(ProductPipeline::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['process'])
            ->getMock();

        $mockPipeline->method('process')->willReturn([]);

        // Replace the service in the container
        static::getContainer()->set(ProductPipeline::class, $mockPipeline);

        $client->request('GET', '/');

        // Ensure the response is successful
        $this->assertResponseIsSuccessful();

        // Check if the "no products available" message is displayed
        $this->assertSelectorExists('.alert-warning');
        $this->assertSelectorTextContains('.alert-warning', 'No products available.');
    }

    public function testMainPageWithSpecificProducts(): void
    {
        $client = static::createClient();

        // Create a mock ProductPipeline to simulate specific products
        $mockPipeline = $this->getMockBuilder(ProductPipeline::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['process'])
            ->getMock();

        $mockPipeline->method('process')->willReturn([
            [
                'title' => 'Product 1',
                'description' => 'Description of Product 1',
                'image_link' => 'https://example.com/image1.jpg',
                'price' => '100.00',
            ],
            [
                'title' => 'Product 2',
                'description' => 'Description of Product 2',
                'image_link' => 'https://example.com/image2.jpg',
                'price' => '200.00',
            ],
        ]);

        // Replace the service in the container
        static::getContainer()->set(ProductPipeline::class, $mockPipeline);

        $crawler = $client->request('GET', '/');

        // Ensure the response is successful
        $this->assertResponseIsSuccessful();

        // Check the product cards
        $this->assertSelectorExists('.card');
        $this->assertEquals(2, $crawler->filter('.card')->count());

        // Validate the content of the first product card
        $firstCard = $crawler->filter('.card')->eq(0);
        $this->assertStringContainsString('Product 1', $firstCard->filter('.card-title')->text());
        $this->assertStringContainsString('Description of Product 1', $firstCard->filter('.card-text')->eq(0)->text());
        $this->assertStringContainsString('$100.00', $firstCard->filter('.card-text')->eq(1)->text());

        // Validate the content of the second product card
        $secondCard = $crawler->filter('.card')->eq(1);
        $this->assertStringContainsString('Product 2', $secondCard->filter('.card-title')->text());
        $this->assertStringContainsString('Description of Product 2', $secondCard->filter('.card-text')->eq(0)->text());
        $this->assertStringContainsString('$200.00', $secondCard->filter('.card-text')->eq(1)->text());
    }

}
