<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Api\Constraint;

use Chaplean\Bundle\ApiClientBundle\Api\Constraint\CallbackConstraint;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection;
use PHPUnit\Framework\TestCase;

/**
 * Class CallbackConstraintTest.
 *
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class CallbackConstraintTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Constraint\CallbackConstraint::validate()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Constraint\CallbackConstraint::__construct()
     *
     * @return void
     */
    public function testCallbackWithoutError()
    {
        $constraint = new CallbackConstraint(
            function ($value, $errors) {
            }
        );
        $errors = new ParameterConstraintViolationCollection();

        $constraint->validate('value', $errors);
        $this->assertTrue($errors->isEmpty());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Constraint\CallbackConstraint::validate()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Constraint\CallbackConstraint::__construct()
     *
     * @return void
     */
    public function testCallbackWithError()
    {
        $constraint = new CallbackConstraint(
            function ($value, $errors) {
                $errors->add('Error');
            }
        );
        $errors = new ParameterConstraintViolationCollection();

        $constraint->validate('value', $errors);
        $this->assertFalse($errors->isEmpty());
    }
}
