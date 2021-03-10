<?php

namespace Chaplean\Bundle\ApiClientBundle\Event;

use Chaplean\Bundle\ApiClientBundle\Api\ResponseInterface;

if (class_exists(\Symfony\Contracts\EventDispatcher\Event::class)) {
    /**
     * Class RequestExecutedEvent.
     *
     * @package   Chaplean\Bundle\ApiClientBundle\Event
     * @author    Matthias - Chaplean <matthias@chaplean.coop>
     * @copyright 2018 Chaplean (http://www.chaplean.coop)
     * @since     1.0.0
     */
    class RequestExecutedEvent extends \Symfony\Contracts\EventDispatcher\Event
    {
        /**
         * @var string
         */
        protected $apiName;

        /**
         * @var ResponseInterface
         */
        protected $response;

        /**
         * RequestExecutedEvent constructor.
         *
         * @param ResponseInterface $response
         * @param string            $apiName
         */
        public function __construct(ResponseInterface $response, string $apiName)
        {
            $this->apiName = $apiName;
            $this->response = $response;
        }

        /**
         * Returns request's response
         *
         * @return ResponseInterface
         */
        public function getResponse(): ResponseInterface
        {
            return $this->response;
        }

        /**
         * @return string
         */
        public function getApiName(): string
        {
            return $this->apiName;
        }
    }
} elseif (class_exists(\Symfony\Component\EventDispatcher\Event::class)) {
    /**
     * Class RequestExecutedEvent.
     *
     * @package   Chaplean\Bundle\ApiClientBundle\Event
     * @author    Matthias - Chaplean <matthias@chaplean.coop>
     * @copyright 2018 Chaplean (http://www.chaplean.coop)
     * @since     1.0.0
     */
    class RequestExecutedEvent extends \Symfony\Component\EventDispatcher\Event
    {
        /**
         * @var string
         */
        protected $apiName;

        /**
         * @var ResponseInterface
         */
        protected $response;

        /**
         * RequestExecutedEvent constructor.
         *
         * @param ResponseInterface $response
         * @param string            $apiName
         */
        public function __construct(ResponseInterface $response, string $apiName)
        {
            $this->apiName = $apiName;
            $this->response = $response;
        }

        /**
         * Returns request's response
         *
         * @return ResponseInterface
         */
        public function getResponse(): ResponseInterface
        {
            return $this->response;
        }

        /**
         * @return string
         */
        public function getApiName(): string
        {
            return $this->apiName;
        }
    }
}
