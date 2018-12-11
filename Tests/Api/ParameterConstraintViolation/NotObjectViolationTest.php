<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation;

use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\NotObjectViolation;
use PHPUnit\Framework\TestCase;

/**
 * Class NotObjectViolationTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class NotObjectViolationTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\NotObjectViolation::__construct()
     *
     * @return void
     */
    public function testMessage()
    {
        $violation = new NotObjectViolation();

        $this->assertEquals(
            'All keys must be strings in an ObjectParameter',
            $violation->getMessage()
        );
    }
}
