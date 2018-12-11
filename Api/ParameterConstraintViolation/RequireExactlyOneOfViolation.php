<?php

namespace Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation;

use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation;

/**
 * Class RequireExactlyOneOfViolation.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Exception
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class RequireExactlyOneOfViolation extends ParameterConstraintViolation
{
    /**
     * RequireExactlyOneOfViolation constructor.
     */
    public function __construct()
    {
        parent::__construct('No value or more than one entry. Only one value must be entered');
    }
}
