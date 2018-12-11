<?php

namespace Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation;

use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation;

/**
 * Class MissingParameterViolation.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Exception
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class MissingParameterViolation extends ParameterConstraintViolation
{
    /**
     * MissingParameterViolation constructor.
     *
     * @param string $parameter
     */
    public function __construct($parameter)
    {
        parent::__construct(sprintf('Parameter was not given and is not optional: "%s"', $parameter));
    }
}
