<?php

namespace App\Connector;

class ProductConnectorCrm implements ProductConnector
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
        return 'CRM';
    }
}