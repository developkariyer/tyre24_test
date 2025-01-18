<?php

namespace App\Connector;

class ProductConnectorNoSql implements ProductConnector
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
        return 'NoSql';
    }
}