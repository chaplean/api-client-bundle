<?php

namespace Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation;

use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation;

/**
 * Class RequireAtLeastOneOfViolation.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Exception
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class RequireAtLeastOneOfViolation extends ParameterConstraintViolation
{
    /**
     * RequireAtLeastOneOfViolation constructor.
     */
    public function __construct()
    {
        parent::__construct('No required value was entered');
    }
}
