<?php

namespace App\Connector;

class ProductConnectorErp implements ProductConnector
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
        return 'ERP';
    }
}