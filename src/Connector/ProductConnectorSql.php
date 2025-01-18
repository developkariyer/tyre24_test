<?php

namespace App\Connector;

class ProductConnectorSql implements ProductConnector
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
        return 'SQL';
    }
}