<?php

namespace Chaplean\Bundle\ApiClientBundle\Api;

/**
 * Class AbstractResponse.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Api
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
abstract class AbstractResponse implements ResponseInterface
{
    /**
     * @var string
     */
    private $uuid;

    /**
     * AbstractResponse constructor.
     */
    public function __construct()
    {
        // Create an unique id for each response (use for logging)
        $this->uuid = bin2hex(random_bytes(16));
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }
}
