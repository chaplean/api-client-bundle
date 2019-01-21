<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Api\Parameter;

use Chaplean\Bundle\ApiClientBundle\Api\Parameter;
use PHPUnit\Framework\TestCase;

/**
 * Class DateTimeParameterTest.
 *
 * @package Tests\Chaplean\Bundle\ApiClientBundle\Api\Parameter
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class DateTimeParameterTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\DateTimeParameter::parameterToArray()
     *
     * @return void
     */
    public function testSerializesToAFormatedString()
    {
        $parameter = Parameter::object([
            'date' => Parameter::dateTime(),
        ]);

        $date = new \DateTime();
        $parameter->setValue(['date' => $date]);

        $result = $parameter->exportForRequest();
        $this->assertEquals($date->format('Y-m-d'), $result['date']);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\DateTimeParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\DateTimeParameter::parameterToArray()
     *
     * @return void
     */
    public function testConfigurableFormat()
    {
        $parameter = Parameter::object([
            'date' => Parameter::dateTime('d-m-Y'),
        ]);

        $date = new \DateTime();
        $parameter->setValue(['date' => $date]);

        $result = $parameter->exportForRequest();
        $this->assertEquals($date->format('d-m-Y'), $result['date']);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\DateTimeParameter::__construct()
     *
     * @return void
     */
    public function testValidation()
    {
        $parameter = Parameter::dateTime();
        $parameter->setValue(new \DateTime());

        $this->assertTrue($parameter->isValid());

        $parameter->setValue(42);

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::dateTime()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::parameterToArray()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\Parameter::exportForRequest()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::parameterToArray()
     *
     * @return void
     */
    public function testToArray()
    {
        $parameter = Parameter::dateTime();

        $value = new \DateTime();

        $parameter->setValue($value);

        $this->assertEquals(
            $value->format('Y-m-d'),
            $parameter->exportForRequest()
        );
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::arrayList()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::parameterToArray()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\Parameter::exportForRequest()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::parameterToArray()
     *
     * @expectedException \Chaplean\Bundle\ApiClientBundle\Exception\ParameterConstraintValidationFailedException
     * @return void
     */
    public function testToArrayInvalidDataThrowsException()
    {
        $parameter = Parameter::dateTime();

        $parameter->exportForRequest();
    }
}
