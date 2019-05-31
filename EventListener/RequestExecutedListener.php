<?php

namespace Chaplean\Bundle\ApiClientBundle\EventListener;

use Chaplean\Bundle\ApiClientBundle\Event\RequestExecutedEvent;
use Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility;
use Chaplean\Bundle\ApiClientBundle\Utility\EmailUtility;

/**
 * Class RequestExecutedListenerTest.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\EventListener
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class RequestExecutedListener
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * @var ApiLogUtility
     */
    protected $apiLogUtility;

    /**
     * @var EmailUtility
     */
    protected $emailUtility;

    /**
     * RequestExecutedListener constructor.
     *
     * @param array         $parameters
     * @param ApiLogUtility $apiLogUtility
     * @param EmailUtility  $emailUtility
     */
    public function __construct(array $parameters, ApiLogUtility $apiLogUtility, EmailUtility $emailUtility)
    {
        $this->parameters = $parameters;
        $this->apiLogUtility = $apiLogUtility;
        $this->emailUtility = $emailUtility;
    }

    /**
     * Persists in database a log entity representing the request just ran.
     *
     * @param RequestExecutedEvent $event
     *
     * @return void
     */
    public function onRequestExecuted(RequestExecutedEvent $event)
    {
        $response = $event->getResponse();

        if ($this->isEnabledLoggingFor('enable_database_logging', $event->getApiName())) {
            $this->apiLogUtility->logResponse($response);
        }

        if ($this->isEnabledLoggingFor('enable_email_logging', $event->getApiName())) {
            $this->emailUtility->sendRequestExecutedNotificationEmail($response);
        }
    }

    /**
     * @param string $logType
     * @param string $apiName
     *
     * @return boolean
     */
    public function isEnabledLoggingFor(string $logType, string $apiName): bool
    {
        if (!array_key_exists($logType, $this->parameters)) {
            return false;
        }

        if ($this->parameters[$logType] === null) {
            return true;
        }

        $isEnabled = in_array($apiName, $this->parameters[$logType]['elements'], true);

        if ($this->parameters[$logType]['type'] === 'exclusive') {
            return !$isEnabled;
        }

        return $isEnabled;
    }
}
