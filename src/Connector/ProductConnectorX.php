<?php

namespace App\Connector;

class ProductConnectorX implements ProductConnector
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
        return 'X';
    }
}