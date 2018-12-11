<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation;

use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\RequireExactlyOneOfViolation;
use PHPUnit\Framework\TestCase;

/**
 * Class RequireExactlyOneOfViolationTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class RequireExactlyOneOfViolationTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\RequireExactlyOneOfViolation::__construct()
     *
     * @return void
     */
    public function testMessage()
    {
        $violation = new RequireExactlyOneOfViolation();

        $this->assertEquals(
            'No value or more than one entry. Only one value must be entered',
            $violation->getMessage()
        );
    }
}
