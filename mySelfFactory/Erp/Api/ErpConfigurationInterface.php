<?php
declare(strict_types=1);

namespace Beside\Erp\Api;

/**
 * Interface ErpConfigurationInterface
 *
 * @package Beside\Erp\Api
 */
interface ErpConfigurationInterface
{
    /**
     * XML path to cron schedule expression for message sending
     */
    public const XML_PATH_CRON_SCHEDULE_SEND = 'beside_erp/general/cron_schedule_send';

    /**
     * XML path to cron schedule expression for messages deletion
     */
    public const XML_PATH_CRON_SCHEDULE_DELETE = 'beside_erp/general/cron_schedule_delete';

    /**
     * XML path to timeout config for waiting for connection with the API
     */
    public const XML_PATH_CONNECTION_TIMEOUT = 'beside_erp/general/connection_timeout';

    /**
     * XML path to timeout config for execution timeout
     */
    public const XML_PATH_EXECUTION_TIMEOUT = 'beside_erp/general/execution_timeout';

    /**
     * XML path to retry attempts count
     */
    public const XML_PATH_ATTEMPTS_COUNT = 'beside_erp/general/attempts_count';

    /**
     * XML path to alert email recipient address
     */
    public const XML_PATH_ALERT_EMAIL = 'beside_erp/general/alert_email';

    /**
     * XML path to days count for queue messages
     */
    public const XML_PATH_DAYS_COUNT = 'beside_erp/general/days_count';

    /**
     * Get cron schedule expression for message sending
     *
     * @return string|null
     */
    public function getCronSendingScheduleValue(): ?string;

    /**
     * Get cron schedule expression for message deletion
     *
     * @return string|null
     */
    public function getCronDeletionScheduleValue(): ?string;

    /**
     * Get connection timeout
     *
     * @return int|null
     */
    public function getConnectionTimeout(): ?int;

    /**
     * Get execution timeout
     *
     * @return int|null
     */
    public function getExecutionTimeout(): ?int;

    /**
     * Get retry attempt count
     *
     * @return int|null
     */
    public function getAttemptsCount(): ?int;

    /**
     * Get alert email recipient address
     *
     * @return string|null
     */
    public function getAlertEmail(): ?string;

    /**
     * Get days count for queue messages
     *
     * @return int|null
     */
    public function getDaysCount(): ?int;
}
