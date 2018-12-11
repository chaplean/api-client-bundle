<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Api\Parameter;

use Chaplean\Bundle\ApiClientBundle\Api\Parameter;
use PHPUnit\Framework\TestCase;

/**
 * Class ArrayParameterTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Api
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class ArrayParameterTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::arrayList()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::validate()
     *
     * @return void
     */
    public function testEmptyArrayWithEmptyArray()
    {
        $parameter = Parameter::arrayList(Parameter::int());
        $parameter->setValue([]);

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::arrayList()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::validate()
     *
     * @return void
     */
    public function testArrayWithArray()
    {
        $parameter = Parameter::arrayList(Parameter::int());
        $parameter->setValue([42]);

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::arrayList()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::validate()
     *
     * @return void
     */
    public function testArrayWithInvalidSubParameter()
    {
        $parameter = Parameter::arrayList(Parameter::int());
        $parameter->setValue([3.14]);

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::arrayList()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::validate()
     *
     * @return void
     */
    public function testArrayValidateInnerArray()
    {
        $parameter = Parameter::arrayList(
            Parameter::arrayList(Parameter::int())
        );
        $parameter->setValue([[42]]);

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::arrayList()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::validate()
     *
     * @return void
     */
    public function testArrayValidateInnerArrayWithInvalidData()
    {
        $parameter = Parameter::arrayList(
            Parameter::arrayList(Parameter::int())
        );
        $parameter->setValue([[3.14]]);

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::arrayList()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::validate()
     *
     * @return void
     */
    public function testArrayWithoutValue()
    {
        $parameter = Parameter::arrayList(Parameter::int());

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::arrayList()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::validate()
     *
     * @return void
     */
    public function testArrayWithObject()
    {
        $parameter = Parameter::arrayList(Parameter::int());
        $parameter->setValue(['value' => 42]);

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::arrayList()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::validate()
     *
     * @return void
     */
    public function testArrayWithString()
    {
        $parameter = Parameter::arrayList(Parameter::int());
        $parameter->setValue('array');

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::arrayList()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::validate()
     *
     * @return void
     */
    public function testDefaultArray()
    {
        $parameter = Parameter::arrayList(Parameter::bool())->defaultValue([true]);

        $this->assertTrue($parameter->isValid());

        $parameter->setValue([true]);

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::arrayList()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::validate()
     *
     * @return void
     */
    public function testOptionalArray()
    {
        $parameter = Parameter::arrayList(Parameter::bool())->optional();

        $this->assertTrue($parameter->isValid());

        $parameter->setValue([true]);

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::arrayList()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::validate()
     *
     * @return void
     */
    public function testArrayDefaultValuesBad()
    {
        $parameter = Parameter::arrayList(Parameter::string());

        $this->assertFalse($parameter->isValid());

        $parameter->setValue(['value']);
        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::arrayList()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::validate()
     *
     * @return void
     */
    public function testArrayDefaultValues()
    {
        $parameter = Parameter::arrayList(Parameter::string()->defaultValue('value'));

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::arrayList()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::validate()
     *
     * @return void
     */
    public function testArrayObject()
    {
        $parameter = Parameter::arrayList(
            Parameter::object(
                [
                    'key' => Parameter::string()
                ]
            )
        );

        $this->assertFalse($parameter->isValid());
        $parameter->setValue(
            [
                [
                    'key' => 'value',
                ]
            ]
        );
        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::arrayList()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::validate()
     *
     * @return void
     */
    public function testArrayObjectDefaultValue()
    {
        $parameter = Parameter::arrayList(
            Parameter::object(
                [
                    'key' => Parameter::string()
                ]
            )->defaultValue(
                [
                    'key' => 'value'
                ]
            )
        );

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::arrayList()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::validate()
     *
     * @return void
     */
    public function testArrayObjectDefaultValueObject()
    {
        $parameter = Parameter::arrayList(
            Parameter::object(
                [
                    'key' => Parameter::string()->defaultValue('value')
                ]
            )
        );

        $this->assertFalse($parameter->isValid());
    }
}
