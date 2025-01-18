<?php

namespace App\Connector;

interface ProductConnector
{
    /**
     * Low-level connector for fetching data
     * @return array Raw data
     */
    public function fetchData(): array;
}