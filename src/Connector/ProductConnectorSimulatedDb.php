<?php

namespace App\Connector;

use RuntimeException;
use Symfony\Component\HttpKernel\KernelInterface;

class ProductConnectorSimulatedDb implements ProductConnector
{
    private const simulatedDbFileName = 'SimulatedDb.csv';
    private string $dbFilePath;

    public function __construct(KernelInterface $kernel)
    {
        $this->dbFilePath = $kernel->getProjectDir() . '/src/Connector/' . self::simulatedDbFileName;
    }

    /**
     * @inheritDoc
     */
    public function fetchData(): array
    {
        if (!file_exists($this->dbFilePath) || !is_readable($this->dbFilePath)) {
            throw new RuntimeException('CSV file not found or not readable: ' . $this->dbFilePath);
        }
        $data = array_map(fn($line) => str_getcsv($line, ';'), file($this->dbFilePath));
        if (empty($data)) {
            throw new RuntimeException('CSV file is empty: ' . $this->dbFilePath);
        }
        $header = array_shift($data);
        $result = [];
        foreach ($data as $row) {
            if (count($row) !== count($header)) {
                continue;
            }
            $result[] = array_combine($header, $row);
        }
        return $result;
    }

    public function getName(): string
    {
        return 'SimulatedDb';
    }
}