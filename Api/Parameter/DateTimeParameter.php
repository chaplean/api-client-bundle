<?php

namespace Chaplean\Bundle\ApiClientBundle\Api\Parameter;

use Chaplean\Bundle\ApiClientBundle\Api\Parameter;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\InvalidTypeViolation;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\MissingParameterViolation;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection;

/**
 * Class DateTimeParameter.
 *
 * @package Chaplean\Bundle\ApiClientBundle\Api\Parameter
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class DateTimeParameter extends Parameter
{
    /**
     * @var string
     */
    protected $format;

    /**
     * Parameter constructor.
     * Don't use the function directly, use the static builders to get a new Parameter.
     *
     * @param string $format
     */
    public function __construct($format)
    {
        parent::__construct();

        $this->format = $format;

        $this->addConstraint(function($value, ParameterConstraintViolationCollection $violations) {
            if ($value === null) {
                $violations->add(new MissingParameterViolation(''));
            } else if (!$value instanceof \DateTime) {
                $violations->add(new InvalidTypeViolation($value, \DateTime::class));
            }
        });
    }

    /**
     * Transform this parameter into its array value
     *
     * @return mixed
     */
    protected function parameterToArray()
    {
        return $this->value->format($this->format);
    }
}
