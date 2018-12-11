<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Exception;

use Chaplean\Bundle\ApiClientBundle\Exception\ParameterConstraintValidationFailedException;
use PHPUnit\Framework\TestCase;

/**
 * Class ParameterConstraintValidationFailedExceptionTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Exception
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class ParameterConstraintValidationFailedExceptionTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Exception\ParameterConstraintValidationFailedException::__construct()
     *
     * @return void
     */
    public function testMessage()
    {
        $violation = new ParameterConstraintValidationFailedException();

        $this->assertEquals(
            '',
            $violation->getMessage()
        );
    }
}
