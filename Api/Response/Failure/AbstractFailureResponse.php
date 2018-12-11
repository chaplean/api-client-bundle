<?php

namespace Chaplean\Bundle\ApiClientBundle\Api\Response\Failure;

use Chaplean\Bundle\ApiClientBundle\Api\AbstractResponse;

/**
 * Class Response.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
abstract class AbstractFailureResponse extends AbstractResponse
{
    protected $method;
    protected $url;
    protected $data;

    /**
     * AbstractResponse constructor.
     *
     * @param string $method
     * @param string $url
     * @param array  $data
     */
    public function __construct($method, $url, array $data)
    {
        parent::__construct();

        $this->method = $method;
        $this->url = $url;
        $this->data = $data;
    }

    /**
     * Returns the called url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Returns the called method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Returns the data given to Guzzle to perform the request
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Returns the status code returned or 0 if the request failed to execute
     *
     * @return integer
     */
    public function getCode()
    {
        return 0;
    }

    /**
     * Wether or not the status code indicates a success request
     *
     * @return boolean
     */
    public function succeeded()
    {
        return false;
    }

    /**
     * Returns the collection of violations on the validated bound data
     * or null if there's none
     *
     * @return ParameterConstraintViolationCollection|null
     */
    public function getViolations()
    {
        return null;
    }
}
