<?php

namespace App\Connector;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class ProductConnectorCollector implements IteratorAggregate
{
    private array $connectors;

    public function __construct(
        ProductConnectorSimulatedDb $productConnectorSimulatedDb,
        ProductConnectorJson $productConnectorJson,
        ProductConnectorXml $productConnectorXml,
        ProductConnectorCsv $productConnectorCsv
    )
    {
        $this->connectors = [
            $productConnectorSimulatedDb,
            $productConnectorJson,
            $productConnectorXml,
            $productConnectorCsv,
        ];
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->connectors);
    }
}