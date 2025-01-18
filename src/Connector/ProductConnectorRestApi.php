<?php

namespace App\Connector;

class ProductConnectorRestApi implements ProductConnector
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
        return 'RestAPI';
    }
}