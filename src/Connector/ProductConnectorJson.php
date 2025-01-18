<?php

namespace App\Connector;

class ProductConnectorJson implements ProductConnector
{
    /**
     * @inheritDoc
     */
    public function fetchData(): array
    {
        return [];
    }
}