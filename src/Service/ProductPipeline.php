<?php

namespace App\Service;

use App\Connector\ProductConnectorCollector;
use ReflectionClass;
use ReflectionException;

class ProductPipeline
{
    /**
     * Templates for normalizing data from different sources.
     * Key is the name of the connector class and value is the template for normalizing data.
     */
    private array $templates = [
        'ProductConnectorSimulatedDb' => [
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
     * @throws ReflectionException
     */
    public function process(): array
    {
        $unifiedData = [];
        foreach ($this->connectors as $connector) {
            $rawData = $connector->fetchData();
            $connectorName = (new ReflectionClass($connector))->getShortName();
            $template = $this->templates[$connectorName] ?? [];
            if (empty($template)) {
                error_log('No template found for ' . $connectorName);
                continue;
            }
            $this->normalizer->setTemplate($template);
            $unifiedData = array_merge($unifiedData, $this->normalizer->normalize($rawData));
        }
        return $unifiedData;
    }

}