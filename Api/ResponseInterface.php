<?php

namespace Chaplean\Bundle\ApiClientBundle\Api;

/**
 * Interface ResponseInterface.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Api
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
interface ResponseInterface
{
    /**
     * Returns the called url
     *
     * @return string
     */
    public function getUrl();

    /**
     * Returns the called method
     *
     * @return string
     */
    public function getMethod();

    /**
     * Returns the data given to Guzzle to perform the request
     *
     * @return array
     */
    public function getData();

    /**
     * Returns the status code returned or 0 if the request failed to execute
     *
     * @return integer
     */
    public function getCode();

    /**
     * Returns the content of the response to the executed request
     * or the error message if the request failed to execute
     *
     * @return string|array
     */
    public function getContent();

    /**
     * Wether or not the status code indicates a success request
     *
     * @return boolean
     */
    public function succeeded();

    /**
     * Returns the collection of violations on the validated bound data
     * or null if there's none
     *
     * @return ParameterConstraintViolationCollection|null
     */
    public function getViolations();

    /**
     * Return an unique identifier
     * Use for logging request call
     *
     * @return string
     */
    public function getUuid();
}
