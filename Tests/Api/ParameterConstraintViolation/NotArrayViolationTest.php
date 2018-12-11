<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation;

use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\NotArrayViolation;
use PHPUnit\Framework\TestCase;

/**
 * Class NotArrayViolationTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class NotArrayViolationTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\NotArrayViolation::__construct()
     *
     * @return void
     */
    public function testMessage()
    {
        $violation = new NotArrayViolation();

        $this->assertEquals(
            'All keys must be plain integers in an ArrayParameter',
            $violation->getMessage()
        );
    }
}
