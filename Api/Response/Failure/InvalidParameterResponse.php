<?php

namespace Chaplean\Bundle\ApiClientBundle\Api\Response\Failure;

use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection;

/**
 * Class InvalidParameterResponse.
 *
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class InvalidParameterResponse extends AbstractFailureResponse
{
    /**
     * @var ParameterConstraintViolationCollection
     */
    private $violations;

    /**
     * InvalidParameterResponse constructor.
     *
     * @param ParameterConstraintViolationCollection $violations
     * @param string                                 $method
     * @param string                                 $url
     * @param array                                  $data
     */
    public function __construct(ParameterConstraintViolationCollection $violations, $method, $url, array $data)
    {
        parent::__construct($method, $url, $data);

        $this->violations = $violations;
    }

    /**
     * Returns the content of the response to the executed request
     * or the error message if the request failed to execute
     *
     * @return string|array
     */
    public function getContent()
    {
        return (string) $this->violations;
    }

    /**
     * @return ParameterConstraintViolationCollection
     */
    public function getViolations()
    {
        return $this->violations;
    }
}
