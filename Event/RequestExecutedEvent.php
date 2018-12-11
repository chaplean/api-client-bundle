<?php

namespace Chaplean\Bundle\ApiClientBundle\Event;

use Chaplean\Bundle\ApiClientBundle\Api\ResponseInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class RequestExecutedEvent.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Event
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class RequestExecutedEvent extends Event
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * RequestExecutedEvent constructor.
     *
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Returns request's response
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }
}
