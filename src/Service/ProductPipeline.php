<?php

namespace App\Service;

use App\Connector\ProductConnectorCollector;

class ProductPipeline
{
    /**
     * Templates for normalizing data from different sources.
     * Key is the name of the connector class and value is the template for normalizing data.
     */
    private array $templates = [
        'SimulatedDb' => [
            'id' => ['id'],
            'title' => ['name'],
            'description' => ['manufacturer', 'additional'],
            'image_link' => ['product_image'],
            'price' => ['price'],
        ],
    ];

    /**
     * @param ProductConnectorCollector $connectors Array of ProductConnector for different sources
     * @param ProductNormalizer $normalizer Normalizer service for normalizing data
     */
    public function __construct(
        private readonly ProductConnectorCollector $connectors,
        private readonly ProductNormalizer $normalizer
    ) {}

    /**
     * Process data from all connectors and return normalized data
     * @return array Normalized data
     */
    public function process(): array
    {
        $unifiedData = [];
        foreach ($this->connectors as $connector) {
            $rawData = $connector->fetchData();
            $template = $this->templates[$connector->getName()] ?? [];
            if (empty($template)) {
                error_log('No template found for ' . $connector->getName());
                continue;
            }
            $this->normalizer->setTemplate($template);
            $unifiedData = array_merge($unifiedData, $this->normalizer->normalize($rawData));
        }
        return $unifiedData;
    }

}