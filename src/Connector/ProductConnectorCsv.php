<?php

namespace App\Connector;

class ProductConnectorCsv implements ProductConnector
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
        return 'CSV';
    }
}