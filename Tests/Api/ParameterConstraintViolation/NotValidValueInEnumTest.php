<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation;

use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\NotValidValueInEnumViolation;
use PHPUnit\Framework\TestCase;

/**
 * Class NotValidValueInEnumTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.1.0
 */
class NotValidValueInEnumTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\NotValidValueInEnumViolation::__construct
     *
     * @return void
     */
    public function testMessageViolation(): void
    {
        $violation = new NotValidValueInEnumViolation(1, [2, 3]);

        $this->assertSame('1 (integer) is not valid. Allowed values: (2 (integer), 3 (integer))', $violation->getMessage());
    }
}
