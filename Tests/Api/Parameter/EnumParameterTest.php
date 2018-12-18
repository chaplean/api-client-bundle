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
     * @expectedException \Chaplean\Bundle\ApiClientBundle\Exception\EnumRequiresAtLeastOneVariantException
     * @expectedExceptionMessage Enumeration type require at least one variant
     */
    public function testRequiredAtLeastOneValuesEmpty(): void
    {
        Parameter::enum([]);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::enum
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\EnumParameter::__construct
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\EnumParameter::validate
     *
     * @return void
     */
    public function testEnumValidWithOnlyOneVariant(): void
    {
        $parameter = Parameter::enum([1]);
        $parameter->setValue(1);

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::enum
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\EnumParameter::__construct
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\EnumParameter::validate
     *
     * @return void
     */
    public function testEnumInvalidWithOnlyOneVariant(): void
    {
        $parameter = Parameter::enum([1]);
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

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::enum
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\EnumParameter::__construct
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\EnumParameter::validate
     *
     * @return void
     */
    public function testValidStrictType(): void
    {
        $parameter = Parameter::enum([1, 2]);
        $parameter->setValue('1');

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::enum
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\EnumParameter::__construct
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\EnumParameter::validate
     *
     * @return void
     */
    public function testEnumWorksWithArrayList(): void
    {
        $parameter = Parameter::arrayList(Parameter::enum([1, 2]));
        $parameter->setValue([1, 2]);

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::enum
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\EnumParameter::__construct
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\EnumParameter::validate
     *
     * @return void
     */
    public function testEnumWorksWithArrayListWithInvalidVariant(): void
    {
        $parameter = Parameter::arrayList(Parameter::enum([1, 2]));
        $parameter->setValue([1, 3]);

        $this->assertFalse($parameter->isValid());
    }
}
