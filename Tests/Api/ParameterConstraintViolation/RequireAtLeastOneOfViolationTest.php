<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation;

use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\RequireAtLeastOneOfViolation;
use PHPUnit\Framework\TestCase;

/**
 * Class RequireAtLeastOneOfViolationTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class RequireAtLeastOneOfViolationTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\RequireAtLeastOneOfViolation::__construct()
     *
     * @return void
     */
    public function testMessage()
    {
        $violation = new RequireAtLeastOneOfViolation();

        $this->assertEquals(
            'No required value was entered',
            $violation->getMessage()
        );
    }
}
