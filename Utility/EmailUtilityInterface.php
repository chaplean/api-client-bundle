<?php

namespace Chaplean\Bundle\ApiClientBundle\Utility;

use Chaplean\Bundle\ApiClientBundle\Api\ResponseInterface;

interface EmailUtilityInterface
{
    /**
     * Persists in database a log entity representing the request just ran.
     *
     * @param ResponseInterface $response
     * @param string|null       $apiName
     *
     * @return void
     */
    public function sendRequestExecutedNotificationEmail(ResponseInterface $response, string $apiName = null);

    /**
     * Compares the given $code against the configuration and tells if we need to send
     * a notification.
     *
     * @param integer $code
     *
     * @return boolean
     */
    public function isStatusCodeConfiguredForNotifications($code);

    /**
     * Check if email logging for $apiName is enabled
     *
     * @param string $apiName
     *
     * @return boolean
     * @deprecated Will be moved and refactored in 2.X in RequestExecutedListener::isEnabledLoggingFor(string, string)
     */
    public function isEnabledLoggingFor(string $apiName): bool;
}
