<?php

namespace App\Connector;

class ProductConnectorWebScrap implements ProductConnector
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
        return 'WebScrap';
    }
}