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

    public function getName(): string
    {
        return 'JSON';
    }
}