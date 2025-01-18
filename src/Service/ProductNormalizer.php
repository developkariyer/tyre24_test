<?php

namespace App\Service;

use InvalidArgumentException;

class ProductNormalizer implements Normalizer
{
    private array $template = [];

    /**
     * @inheritDoc
     */
    public function normalize(array $rawData): array
    {
        if (empty($this->template)) {
            throw new InvalidArgumentException('Template is not set');
        }

        return array_map(function ($rawProductData) {
            return $this->normalizeProduct($rawProductData);
        }, $rawData);
    }

    /**
     * Normalize a single product. More complex logic can be added here if needed.
     *
     * @param array $rawProductData Raw data for a single product.
     * @return array Normalized product data.
     */
    private function normalizeProduct(array $rawProductData): array
    {
        $normalizedProductData = [];

        foreach ($this->template as $key => $fields) {
            $values = array_map(
                fn($field) => $rawProductData[$field] ?? '',
                $fields
            );
            $normalizedProductData[$key] = trim(implode(' ', $values));
        }

        return $normalizedProductData;
    }

    /**
     * @inheritDoc
     */
    public function setTemplate(array $template): void
    {
        try {
            $this->validateTemplate($template);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException('Invalid template: ' . $e->getMessage());
        }
        $this->template = $template;
    }

    /**
     * @inheritDoc
     */
    public function getTemplate(): array
    {
        return $this->template;
    }

    /**
     * @inheritDoc
     */
    public function validateTemplate(array $template): void
    {
        foreach ($template as $key => $value) {
            if (!is_string($key)) {
                throw new InvalidArgumentException("Template key must be a string, but '$key' is not.");
            }
            if (!in_array($key, static::requiredKeys, true)) {
                throw new InvalidArgumentException("Template key '$key' is not allowed.");
            }
            if (!is_array($value) || !$this->allStrings($value)) {
                throw new InvalidArgumentException("Template value for '$key' must be an array of strings.");
            }
        }
        $missingKeys = array_diff(static::requiredKeys, array_keys($template));
        if (!empty($missingKeys)) {
            throw new InvalidArgumentException('Template is missing required key(s): ' . implode(', ', $missingKeys));
        }
    }

    /**
     * Check if all items in an array are strings
     *
     * @param array $items Array to check
     * @return bool True if all items are strings, false otherwise
     */
    private function allStrings(array $items): bool
    {
        foreach ($items as $item) {
            if (!is_string($item)) {
                return false;
            }
        }
        return true;
    }
}