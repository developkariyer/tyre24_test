<?php

namespace App\Connector;

class ProductConnectorTsv implements ProductConnector
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
        return 'TSV';
    }
}