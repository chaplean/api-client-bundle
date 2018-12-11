<?php

namespace Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation;

use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation;

/**
 * Class NotObjectViolation.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Exception
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class NotObjectViolation extends ParameterConstraintViolation
{
    /**
     * NotObjectViolation constructor.
     */
    public function __construct()
    {
        parent::__construct('All keys must be strings in an ObjectParameter');
    }
}
