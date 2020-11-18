<?php
declare(strict_types=1);

namespace Beside\Erp\Model;

use Beside\Erp\Api\ErpApiInterface;
use Beside\Erp\Api\ErpConfigurationInterface;
use Exception;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\HTTP\Client\Curl;
use Psr\Log\LoggerInterface;
use Magento\Framework\HTTP\Client\CurlFactory;

/**
 * Class ErpApi
 *
 * @package Beside\Erp\Model
 */
class ErpApi implements ErpApiInterface
{
    /**
     * Api type
     * @var string
     */
    public $apiType;

    /**
     * @var ErpConfigurationInterface
     */
    private ErpConfigurationInterface $erpConfiguration;

    /**
     * @var CurlFactory
     */
    private CurlFactory $curlFactory;

    /**
     * @var Json
     */
    public Json $json;

    /**
     * @var LoggerInterface
     */
    public LoggerInterface $logger;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * ErpApi constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param ErpConfigurationInterface $erpConfiguration
     * @param CurlFactory $curlFactory
     * @param Json $json
     * @param LoggerInterface $logger
     * @param string $apiType
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErpConfigurationInterface $erpConfiguration,
        CurlFactory $curlFactory,
        Json $json,
        LoggerInterface $logger,
        string $apiType
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->erpConfiguration = $erpConfiguration;
        $this->curlFactory = $curlFactory;
        $this->json = $json;
        $this->logger = $logger;
        $this->apiType = $apiType;
    }

    /**
     * Get API type
     *
     * @return string|null
     *
     * @throws Exception
     */
    public function getApiType(): ?string
    {
        if ($this->apiType === null) {
            throw new Exception('API type is not defined');
        } else {
            return $this->apiType;
        }
    }

    /**
     * Send request to API based on api_type
     *
     * @param $data
     * @return array
     * @throws Exception
     */
    public function sendRequest($data): array
    {
        if (!$this->isApiEnabled()) {
            $result = ['response' => '', 'errors' => 'API disabled'];
        } else {
            /** @var Curl $curl */
            $curl = $this->curlFactory->create() ;
            $headers['Content-Type'] = 'application/json';
            $url = $this->getEndpointUrl();
            $options = $this->getOptions();
            $curl->setHeaders($headers);
            $curl->setOption(CURLOPT_CONNECTTIMEOUT, $options['connection_timeout']);
            $curl->setOption(CURLOPT_TIMEOUT, $options['execution_timeout']);
            try {
                $curl->post($url, $data);
                $body = $curl->getBody();
                $this->logger->debug(json_encode(
                    [
                        'url' => $url,
                        'data' => $data,
                        'response_headers' => $this->json->serialize($curl->getHeaders()),
                        'response' => $body,
                    ]
                ));
                $result = $this->parseResponse($curl);
            } catch (Exception $e) {
                $errorMessage = $e->getMessage();
                if (!$errorMessage) {
                    $errorMessage = __('An error has occurred while sending request');
                }
                $this->logger->error($errorMessage);
                $result = ['response' => '', 'errors' => $errorMessage];
            }
        }

        return $result;
    }

    /**
     * Parse curl response
     *
     * @param Curl $curl
     *
     * @return array
     */
    private function parseResponse(Curl $curl): array
    {
        $errors = null;
        $response = [];
        if ($curl->getStatus() === 200) {
            $response = $curl->getBody();
            try {
                $response = $this->json->unserialize($response);
            } catch (Exception $e) {
                $errors = __($e->getMessage());
            }
        } else {
            $errors = __('Bad response ' . $curl->getStatus());
        }

        return ['response' => $response, 'errors' => $errors];
    }

    /**
     * Get API endpoint URL
     *
     * @return string|null
     * @throws Exception
     */
    public function getEndpointUrl(): ?string
    {
        $apiType = $this->getApiType();
        return $this->scopeConfig->getValue('beside_erp/' . $apiType .'/api_url');
    }

    /**
     * Get curl options
     *
     * @return array
     * @throws Exception
     */
    public function getOptions(): array
    {
        $apiType = $this->getApiType();
        $options = [
            'attempts_count' => $this->erpConfiguration->getAttemptsCount(),
            'execution_timeout' => $this->erpConfiguration->getExecutionTimeout(),
            'connection_timeout' => $this->erpConfiguration->getConnectionTimeout()
            ];

        return $options;
    }

    /**
     * Get API credentials
     *
     * @return string|null
     * @throws Exception
     */
    public function getCredentials(): ?string
    {
        $apiType = $this->getApiType();
        return $this->scopeConfig->getValue('beside_erp/' . $apiType .'/bearer');
    }

    /**
     * Check if API is enabled
     *
     * @return bool
     * @throws Exception
     */
    public function isApiEnabled(): bool
    {
        $apiType = $this->getApiType();
        return (bool) $this->scopeConfig->getValue('beside_erp/' . $apiType .'/active');
    }
}
