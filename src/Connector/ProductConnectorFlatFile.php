<?php

namespace App\Connector;

class ProductConnectorFlatFile implements ProductConnector
{
    /**
     * @inheritDoc
     */
    public function fetchData(): array
    {
        return [];
    }

    public function getName(): string
    {
        return 'FlatFile';
    }
}