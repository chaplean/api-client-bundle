<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Tests\Api\Parameter;

use Chaplean\Bundle\ApiClientBundle\Api\Parameter;
use PHPUnit\Framework\TestCase;

/**
 * Class EnumParameterTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Api\Parameter
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.1.0
 */
class EnumParameterTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::enum
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\EnumParameter::__construct
     *
     * @return void
     *
     * @expectedException \Chaplean\Bundle\ApiClientBundle\Exception\EnumRequiresAtLeastTwoVariantsException
     * @expectedExceptionMessage Enumeration type require at least two variants
     */
    public function testRequiredAtLeastTwoValuesEmpty(): void
    {
        Parameter::enum([]);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::enum
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\EnumParameter::__construct
     *
     * @return void
     *
     * @expectedException \Chaplean\Bundle\ApiClientBundle\Exception\EnumRequiresAtLeastTwoVariantsException
     * @expectedExceptionMessage Enumeration type require at least two variants
     */
    public function testRequiredAtLeast2ValuesOnlyOne(): void
    {
        Parameter::enum([1]);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::enum
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\EnumParameter::__construct
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\EnumParameter::validate
     *
     * @return void
     */
    public function testNotFoundValueInEnum(): void
    {
        $parameter = Parameter::enum([1, 2]);
        $parameter->setValue(0);

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::enum
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\EnumParameter::__construct
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\EnumParameter::validate
     *
     * @return void
     */
    public function testFoundValueInEnum(): void
    {
        $parameter = Parameter::enum([1, 2]);
        $parameter->setValue(1);

        $this->assertTrue($parameter->isValid());
    }
}
