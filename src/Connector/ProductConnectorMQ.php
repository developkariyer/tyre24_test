<?php

namespace App\Connector;

class ProductConnectorMQ implements ProductConnector
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
        return 'MQ';
    }
}