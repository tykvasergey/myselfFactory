<?php
declare(strict_types=1);

namespace Beside\Erp\Model;

use Beside\Erp\Api\ErpConfigurationInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class ErpConfig
 *
 * @package Beside\Erp\Model
 */
class ErpConfig implements ErpConfigurationInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * ErpConfig constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get cron schedule expression for message sending
     *
     * @return string|null
     */
    public function getCronSendingScheduleValue(): ?string
    {
        return $this->getConfigValue(self::XML_PATH_CRON_SCHEDULE_SEND);
    }

    /**
     * Get cron schedule expression for message deletion
     *
     * @return string|null
     */
    public function getCronDeletionScheduleValue(): ?string
    {
        return $this->getConfigValue(self::XML_PATH_CRON_SCHEDULE_DELETE);
    }

    /**
     * Get connection timeout
     *
     * @return int|null
     */
    public function getConnectionTimeout(): ?int
    {
        $value = $this->getConfigValue(self::XML_PATH_CONNECTION_TIMEOUT);

        return $value ? (int) $value : null;
    }

    /**
     * Get execution timeout
     *
     * @return int|null
     */
    public function getExecutionTimeout(): ?int
    {
        $value = $this->getConfigValue(self::XML_PATH_EXECUTION_TIMEOUT);

        return $value ? (int) $value : null;
    }

    /**
     * Get retry attempt count
     *
     * @return int|null
     */
    public function getAttemptsCount(): ?int
    {
        $value = $this->getConfigValue(self::XML_PATH_ATTEMPTS_COUNT);

        return $value ? (int) $value : null;
    }

    /**
     * Get alert email recipient address
     *
     * @return string|null
     */
    public function getAlertEmail(): ?string
    {
        return $this->getConfigValue(self::XML_PATH_ALERT_EMAIL);
    }

    /**
     * Get days count for queue messages
     *
     * @return int|null
     */
    public function getDaysCount(): ?int
    {
        $value = $this->getConfigValue(self::XML_PATH_DAYS_COUNT);

        return $value ? (int) $value : null;
    }

    /**
     * Get config value
     *
     * @param string $path
     *
     * @return string|null
     */
    private function getConfigValue(string $path): ?string
    {
        return $this->scopeConfig->getValue($path);
    }
}
