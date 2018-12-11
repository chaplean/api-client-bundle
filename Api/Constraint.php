<?php

namespace Chaplean\Bundle\ApiClientBundle\Api;

/**
 * Class Constraint.
 *
 * A Constraint is a predicate a value must abide to.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Api
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
abstract class Constraint
{
    /**
     * Run the validation logic and fill the $errors iterator with violations if any
     *
     * @param mixed                                  $value
     * @param ParameterConstraintViolationCollection $errors
     *
     * @return void
     */
    abstract public function validate($value, ParameterConstraintViolationCollection $errors);
}
