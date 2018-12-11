<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Api;

use Chaplean\Bundle\ApiClientBundle\Api\Parameter;
use Chaplean\Bundle\ApiClientBundle\Api\Parameter\DateTimeParameter;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation\MissingParameterViolation;
use PHPUnit\Framework\TestCase;

/**
 * Class ParameterTest.
 *
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class ParameterTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isValid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::getViolations()
     *
     * @return void
     */
    public function testGetViolations()
    {
        $parameter = Parameter::bool();
        $parameter->setValue(true);

        $this->assertTrue($parameter->isValid());
        $this->assertTrue($parameter->getViolations()->isEmpty());

        $parameter->setValue(3.14);

        $this->assertFalse($parameter->isValid());
        $this->assertFalse($parameter->getViolations()->isEmpty());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::addConstraint()
     *
     * @return void
     */
    public function testAddConstraint()
    {
        $parameter = Parameter::bool();
        $parameter->setValue(true);

        $this->assertTrue($parameter->isValid());
        $this->assertTrue($parameter->getViolations()->isEmpty());

        $parameter->addConstraint(function($value, $violations) {
            $violations->add(new MissingParameterViolation(''));
        });

        $this->assertFalse($parameter->isValid());
        $this->assertFalse($parameter->getViolations()->isEmpty());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::addConstraint()
     *
     * @expectedException \Chaplean\Bundle\ApiClientBundle\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "Chaplean\Bundle\ApiClientBundle\Api\Constraint or a callable", "string" given
     *
     * @return void
     */
    public function testAddConstraintInvalidArgument()
    {
        $parameter = Parameter::bool();

        $parameter->addConstraint('test');
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::untyped()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isValid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::validate()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::__construct()
     *
     * @return void
     */
    public function testUntypedWithBool()
    {
        $parameter = Parameter::untyped();
        $parameter->setValue(true);

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::untyped()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isValid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::validate()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::__construct()
     *
     * @return void
     */
    public function testUntypedWithInteger()
    {
        $parameter = Parameter::untyped();
        $parameter->setValue(1);

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::untyped()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isValid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::validate()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::__construct()
     *
     * @return void
     */
    public function testUntypedWithFloat()
    {
        $parameter = Parameter::untyped();
        $parameter->setValue(1.2);

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::untyped()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isValid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::validate()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::__construct()
     *
     * @return void
     */
    public function testUntypedWithString()
    {
        $parameter = Parameter::untyped();
        $parameter->setValue('test');

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::untyped()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isValid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::validate()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::__construct()
     *
     * @return void
     */
    public function testUntypedWithArray()
    {
        $parameter = Parameter::untyped();
        $parameter->setValue([]);

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::bool()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isValid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::validate()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::__construct()
     *
     * @return void
     */
    public function testBoolWithBool()
    {
        $parameter = Parameter::bool();
        $parameter->setValue(true);

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::bool()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isValid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::validate()
     *
     * @return void
     */
    public function testBoolWithoutValue()
    {
        $parameter = Parameter::bool();

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::bool()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isValid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::validate()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::setValue()
     *
     * @return void
     */
    public function testBoolWithInt()
    {
        $parameter = Parameter::bool();
        $parameter->setValue(42);

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::int()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isValid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::validate()
     *
     * @return void
     */
    public function testIntWithInt()
    {
        $parameter = Parameter::int();
        $parameter->setValue(42);

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::int()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isValid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::validate()
     *
     * @return void
     */
    public function testIntWithoutValue()
    {
        $parameter = Parameter::int();

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::int()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isValid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::validate()
     *
     * @return void
     */
    public function testIntWithString()
    {
        $parameter = Parameter::int();
        $parameter->setValue('42');

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::int()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isValid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::validate()
     *
     * @return void
     */
    public function testIntWithFloat()
    {
        $parameter = Parameter::int();
        $parameter->setValue(3.14);

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::float()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isValid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::validate()
     *
     * @return void
     */
    public function testFloatWithFloat()
    {
        $parameter = Parameter::float();
        $parameter->setValue(3.14);

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::float()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isValid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::validate()
     *
     * @return void
     */
    public function testFloatWithoutValue()
    {
        $parameter = Parameter::float();

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::float()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isValid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::validate()
     *
     * @return void
     */
    public function testFloatWithInt()
    {
        $parameter = Parameter::float();
        $parameter->setValue(3);

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::float()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isValid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::validate()
     *
     * @return void
     */
    public function testFloatWithString()
    {
        $parameter = Parameter::float();
        $parameter->setValue('3.14');

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::string()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isValid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::validate()
     *
     * @return void
     */
    public function testStringWithString()
    {
        $parameter = Parameter::string();
        $parameter->setValue('3.14');

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::string()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isValid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::validate()
     *
     * @return void
     */
    public function testStringWithoutValue()
    {
        $parameter = Parameter::string();

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::string()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isValid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::validate()
     *
     * @return void
     */
    public function testStringWithInt()
    {
        $parameter = Parameter::string();
        $parameter->setValue(42);

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::int()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::id()
     *
     * @return void
     */
    public function testId()
    {
        $this->assertEquals(Parameter::id(), Parameter::int());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::dateTime()
     *
     * @return void
     */
    public function testDateTime()
    {
        $this->assertInstanceOf(DateTimeParameter::class, Parameter::dateTime());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::int()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::optional()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isValid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::validate()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::setValue()
     *()
     * @return void
     */
    public function testOptionalValue()
    {
        $parameter = Parameter::int()
            ->optional();

        $this->assertTrue($parameter->isValid());

        $parameter->setValue(42);

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::int()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::defaultValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isValid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::validate()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::setValue()
     *
     * @return void
     */
    public function testDefaultValue()
    {
        $parameter = Parameter::int()
            ->defaultValue(42);

        $this->assertTrue($parameter->isValid());

        $parameter->setValue(42);

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::int()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::isOptional()
     *
     * @return void
     */
    public function testIsOptional()
    {
        $parameter = Parameter::int();
        $this->assertFalse($parameter->isOptional());

        $parameter->optional();
        $this->assertTrue($parameter->isOptional());
    }
}
