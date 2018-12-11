<?php

namespace Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation;

use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation;

/**
 * Class NotArrayViolation.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Exception
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class NotArrayViolation extends ParameterConstraintViolation
{
    /**
     * NotArrayViolation constructor.
     */
    public function __construct()
    {
        parent::__construct('All keys must be plain integers in an ArrayParameter');
    }
}
