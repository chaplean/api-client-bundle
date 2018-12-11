<?php

namespace Chaplean\Bundle\ApiClientBundle\EventListener;

use Chaplean\Bundle\ApiClientBundle\Event\RequestExecutedEvent;
use Chaplean\Bundle\ApiClientBundle\Utility\EmailUtility;
use Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility;

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
     * @param ApiLogUtility $apiLogUtility
     * @param EmailUtility  $emailUtility
     */
    public function __construct(ApiLogUtility $apiLogUtility, EmailUtility $emailUtility)
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

        $this->apiLogUtility->logResponse($response);
        $this->emailUtility->sendRequestExecutedNotificationEmail($response);
    }
}
