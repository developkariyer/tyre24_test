<?php

namespace App\Connector;

class ProductConnectorXml implements ProductConnector
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
        return 'XML';
    }
}