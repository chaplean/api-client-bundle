<?php

namespace Chaplean\Bundle\ApiClientBundle\EventListener;

use Chaplean\Bundle\ApiClientBundle\Event\RequestExecutedEvent;
use Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility;
use Chaplean\Bundle\ApiClientBundle\Utility\EmailUtilityInterface;

/**
 * Class RequestExecutedListenerTest.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\EventListener
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (https://www.chaplean.coop)
 * @since     1.0.0
 */
class RequestExecutedListener
{
    /**
     * @var ApiLogUtility
     */
    protected $apiLogUtility;

    /**
     * @var EmailUtilityInterface
     */
    protected $emailUtility;

    /**
     * RequestExecutedListener constructor.
     *
     * @param ApiLogUtility $apiLogUtility
     * @param EmailUtilityInterface  $emailUtility
     */
    public function __construct(ApiLogUtility $apiLogUtility, EmailUtilityInterface $emailUtility)
    {
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

        $this->apiLogUtility->logResponse($response, $event->getApiName());
        $this->emailUtility->sendRequestExecutedNotificationEmail($response, $event->getApiName());
    }
}
