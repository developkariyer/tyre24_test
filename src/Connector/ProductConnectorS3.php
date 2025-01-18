<?php

namespace App\Connector;

class ProductConnectorS3 implements ProductConnector
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
        return 'S3';
    }
}