<?php

namespace Chaplean\Bundle\ApiClientBundle\Api\Constraint;

use Chaplean\Bundle\ApiClientBundle\Api\Constraint;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection;

/**
 * Class CallbackConstraint.
 *
 * A Constraint with a validation logic taking the form of a single function callback.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Api
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class CallbackConstraint extends Constraint
{
    protected $validationCallback;

    /**
     * CallbackConstraint constructor.
     *
     * @param callable $validationCallback
     */
    public function __construct(callable $validationCallback)
    {
        $this->validationCallback = $validationCallback;
    }

    /**
     * @param mixed                                  $value
     * @param ParameterConstraintViolationCollection $errors
     *
     * @return mixed
     */
    public function validate($value, ParameterConstraintViolationCollection $errors)
    {
        return ($this->validationCallback)($value, $errors);
    }
}
