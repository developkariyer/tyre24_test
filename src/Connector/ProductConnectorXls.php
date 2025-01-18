<?php

namespace App\Connector;

class ProductConnectorXls implements ProductConnector
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
        return 'XLS';
    }
}