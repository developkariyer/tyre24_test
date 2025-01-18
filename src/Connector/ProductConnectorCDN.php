<?php

namespace App\Connector;

class ProductConnectorCDN implements ProductConnector
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
        return 'CDN';
    }
}