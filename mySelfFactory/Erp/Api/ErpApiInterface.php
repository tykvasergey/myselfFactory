<?php
declare(strict_types=1);

namespace Beside\Erp\Api;

use Exception;

/**
 * Interface ErpApiInterface
 *
 * @package Beside\Erp\Api
 */
interface ErpApiInterface
{
    /**
     * Get api type
     *
     * @return string|null
     *
     * @throws Exception
     */
    public function getApiType(): ?string;

    /**
     * Send request to API based on api_type
     *
     * @param $data
     *
     * @return array
     */
    public function sendRequest($data): array;

    /**
     * Get API endpoint URL
     *
     * @return string|null
     */
    public function getEndpointUrl(): ?string;

    /**
     * Get curl options
     *
     * @return array
     */
    public function getOptions(): array;

    /**
     * Get API credentials
     *
     * @return string|null
     */
    public function getCredentials(): ?string;

    /**
     * Check if API is enabled
     *
     * @return bool
     */
    public function isApiEnabled(): bool;
}
