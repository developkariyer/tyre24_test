<?php

namespace App\Connector;

class ProductConnectorGraphQl implements ProductConnector
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
        return 'GraphQL';
    }
}