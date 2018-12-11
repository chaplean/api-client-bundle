<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation;

use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\MissingParameterViolation;
use PHPUnit\Framework\TestCase;

/**
 * Class MissingParameterViolationTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class MissingParameterViolationTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\MissingParameterViolation::__construct()
     *
     * @return void
     */
    public function testMessage()
    {
        $violation = new MissingParameterViolation('toto');

        $this->assertEquals(
            'Parameter was not given and is not optional: "toto"',
            $violation->getMessage()
        );
    }
}
