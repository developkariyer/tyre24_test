<?php

namespace App\Connector;

class ProductConnectorEdi implements ProductConnector
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
        return 'EDI';
    }
}