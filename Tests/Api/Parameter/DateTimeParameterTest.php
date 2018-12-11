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

        $result = $parameter->toArray();
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

        $result = $parameter->toArray();
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
}
