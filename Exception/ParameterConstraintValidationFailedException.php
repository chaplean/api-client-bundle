<?php

namespace Chaplean\Bundle\ApiClientBundle\Exception;

/**
 * Class ParameterConstraintValidationFailedException.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Exception
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class ParameterConstraintValidationFailedException extends \LogicException
{
    /**
     * ParameterConstraintValidationFailedException constructor.
     */
    public function __construct()
    {
        parent::__construct('');
    }
}
