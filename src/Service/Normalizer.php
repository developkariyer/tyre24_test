<?php

namespace App\Service;

/**
 * Interface for normalizing data
 */
interface Normalizer
{
    /**
     * Required keys for normalizing data
     */
    const requiredKeys = ['id', 'title', 'description', 'image_link', 'price'];

    /**
     * Normalize raw data using given template
     * @param array $rawData Raw data to normalize
     * @return array Normalized data
     */
    public function normalize(array $rawData): array;

    /**
     * Set template for normalizing data
     * @param array $template Template for normalizing data
     */
    public function setTemplate(array $template): void;

    /**
     * Get template for normalizing data
     * @return array Template for normalizing data
     */
    public function getTemplate(): array;

    /**
     * Validate template for normalizing data
     *  Validation of template:
     *  - keys must be exact match of required keys
     *  - values must be an array of strings
     * @param array $template Template to validate
     */
    function validateTemplate(array $template): void;
}