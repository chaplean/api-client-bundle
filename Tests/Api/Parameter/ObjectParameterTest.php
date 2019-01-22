<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Api\Parameter;

use Chaplean\Bundle\ApiClientBundle\Api\Parameter;
use PHPUnit\Framework\TestCase;

/**
 * Class ObjectParameterTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Api
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class ObjectParameterTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::validate()
     *
     * @return void
     */
    public function testEmptyObjectWithEmptyObject()
    {
        $parameter = Parameter::object([]);
        $parameter->setValue([]);

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::validate()
     *
     * @return void
     */
    public function testObjectWithObject()
    {
        $parameter = Parameter::object([
            'value' => Parameter::int(),
        ]);
        $parameter->setValue(['value' => 42]);

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::validate()
     *
     * @return void
     */
    public function testObjectWithInvalidSubParameter()
    {
        $parameter = Parameter::object([
            'value' => Parameter::int(),
        ]);
        $parameter->setValue(['value' => 3.14]);

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::validate()
     *
     * @return void
     */
    public function testObjectValidateInnerObject()
    {
        $parameter = Parameter::object([
            'value' => Parameter::object([
                'value' => Parameter::int(),
            ]),
        ]);
        $parameter->setValue(['value' => ['value' => 42]]);

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::validate()
     *
     * @return void
     */
    public function testObjectValidateInnerObjectWithInvalidData()
    {
        $parameter = Parameter::object([
            'value' => Parameter::object([
                'value' => Parameter::int(),
            ]),
        ]);
        $parameter->setValue(['value' => ['value' => 3.14]]);

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::validate()
     *
     * @return void
     */
    public function testObjectWithoutValue()
    {
        $parameter = Parameter::object([]);

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::validate()
     *
     * @return void
     */
    public function testObjectWithArray()
    {
        $parameter = Parameter::object([
            'value' => Parameter::int()
        ]);
        $parameter->setValue([42]);

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::validate()
     *
     * @return void
     */
    public function testObjectWithString()
    {
        $parameter = Parameter::object([]);
        $parameter->setValue('array');

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::validate()
     *
     * @return void
     */
    public function testDefaultObject()
    {
        $parameter = Parameter::object([
            'value' => Parameter::bool()
        ])->defaultValue(['value' => true]);

        $this->assertTrue($parameter->isValid());

        $parameter->setValue(['value' => true]);

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::validate()
     *
     * @return void
     */
    public function testOptionalObject()
    {
        $parameter = Parameter::object([
            'value' => Parameter::bool(),
        ])->optional();

        $this->assertTrue($parameter->isValid());

        $parameter->setValue(['value' => true]);

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::validate()
     *
     * @return void
     */
    public function testExtraDataIsViolation()
    {
        $parameter = Parameter::object([
            'value' => Parameter::bool(),
        ]);

        $parameter->setValue(['value' => true, 'extra' => false]);

        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::validate()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::allowExtraFields
     *
     * @return void
     */
    public function testAllowExtraData()
    {
        $parameter = Parameter::object([
            'value' => Parameter::bool(),
        ])->allowExtraFields();

        $parameter->setValue(['value' => true, 'extra' => false]);

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::validate()
     *
     * @return void
     */
    public function testObjectIsOptionalOnlyWhenNoValueIsProvided()
    {
        $parameter = Parameter::object([
            'value' => Parameter::bool(),
        ])->optional();

        $parameter->setValue(null);
        $this->assertTrue($parameter->isValid());

        $parameter->setValue([]);
        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::exportForRequest()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::parameterToArray()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::parameterToArray()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::parameterToArray()
     *
     * @return void
     */
    public function testExportForRequest()
    {
        $parameter = Parameter::object([
            'value1' => Parameter::int(),
            'value2' => Parameter::bool(),
            'value3' => Parameter::arrayList(
                Parameter::object([
                    'value4' => Parameter::int(),
                    'value5' => Parameter::bool(),
                ])
            )
        ]);

        $value = [
            'value1' => 42,
            'value2' => true,
            'value3' => [
                [
                    'value4' => 42,
                    'value5' => true,
                ],
                [
                    'value4' => 42,
                    'value5' => true,
                ],
            ]
        ];

        $parameter->setValue($value);

        $this->assertEquals(
            $value,
            $parameter->exportForRequest()
        );
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::exportForRequest()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::parameterToArray()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::parameterToArray()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ArrayParameter::parameterToArray()
     *
     * @expectedException \Chaplean\Bundle\ApiClientBundle\Exception\ParameterConstraintValidationFailedException
     * @return void
     */
    public function testExportForRequestInvalidDataThrowsException()
    {
        $parameter = Parameter::object([
            'value1' => Parameter::int(),
            'value2' => Parameter::bool(),
            'value3' => Parameter::arrayList(
                Parameter::object([
                    'value4' => Parameter::int(),
                    'value5' => Parameter::bool(),
                ])
            )
        ]);

        $parameter->exportForRequest();
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::validate()
     *
     * @return void
     */
    public function testObjectNotValid()
    {
        $param = Parameter::object(
            [
                'val' => Parameter::int()
            ]
        );
        $param->setValue([]);

        $this->assertFalse($param->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::validate()
     *
     * @return void
     */
    public function testObjectValid()
    {
        $param = Parameter::object(
            [
                'inner' => Parameter::object([
                    'val' => Parameter::int()->optional()
                ])
            ]
        );

        $this->assertTrue($param->isValid());

        $param->setValue([]);
        $this->assertTrue($param->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::validate()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::requireAtLeastOneOf()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::isRequireParameters()
     *
     * @return void
     */
    public function testRequireAtLeastOneOf()
    {
        $parameter = Parameter::object(
            [
                'value1' => Parameter::int(),
                'value2' => Parameter::int(),
                'value3' => Parameter::int(),
                'value4' => Parameter::int(),
            ]
        );

        $parameter->setValue([]);
        $this->assertFalse($parameter->isValid());

        $parameter->requireAtLeastOneOf(['value1', 'value2', 'value3']);
        $this->assertFalse($parameter->isValid());

        $parameter->setValue(['value1' => 10, 'value4' => 12]);
        $this->assertTrue($parameter->isValid());

        $parameter->setValue(['value1' => 10, 'value2' => 20, 'value3' => 30, 'value4' => 40]);
        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::validate()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::requireExactlyOneOf()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::isRequireParameters()
     *
     * @return void
     */
    public function testRequireExactlyOneOf()
    {
        $parameter = Parameter::object(
            [
                'value1' => Parameter::int(),
                'value2' => Parameter::int(),
                'value3' => Parameter::int(),
                'value4' => Parameter::int()->optional(),
            ]
        );

        $parameter->setValue([]);
        $this->assertFalse($parameter->isValid());

        $parameter->requireExactlyOneOf(['value1', 'value2', 'value3']);
        $this->assertFalse($parameter->isValid());

        $parameter->setValue(['value1' => 10]);
        $this->assertTrue($parameter->isValid());

        $parameter->setValue(['value1' => 10, 'value2' => 20, 'value3' => 30, 'value4' => 40]);
        $this->assertFalse($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::validate()
     *
     * @return void
     */
    public function testDefautValuesBad()
    {
        $parameter = Parameter::object(
            [
                'key' => Parameter::string(),
            ]
        );

        $this->assertFalse($parameter->isValid());

        $parameter->setValue(['key' => 'value']);
        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::validate()
     *
     * @return void
     */
    public function testDefautValues()
    {
        $parameter = Parameter::object(
            [
                'key' => Parameter::string()->defaultValue('value'),
            ]
        );

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::validate()
     *
     * @return void
     */
    public function testDefautValuesMultipleBad()
    {
        $parameter = Parameter::object(
            [
                'key' => Parameter::string()->defaultValue('value'),
                'key2' => Parameter::string(),
            ]
        );

        $this->assertFalse($parameter->isValid());

        $parameter->setValue(['key2' => 'value']);
        $this->assertTrue($parameter->isValid());

        $parameter->setValue(['key2' => 'value', 'key' => 'value']);
        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::object()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::setValue()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::validate()
     *
     * @return void
     */
    public function testDefautValuesMultiple()
    {
        $parameter = Parameter::object(
            [
                'key' => Parameter::string()->defaultValue('value'),
                'key2' => Parameter::string()->defaultValue('value'),
            ]
        );

        $this->assertTrue($parameter->isValid());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter::exportForRequest()
     *
     * @return void
     */
    public function testExportForRequestOnEmptyValueWithOptionalObject()
    {
        $parameter = Parameter::object(['test' => Parameter::int()->optional()]);
        $this->assertEquals([], $parameter->exportForRequest());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::getParameter
     *
     * @return void
     */
    public function testGetParameter(): void
    {
        $parameter = Parameter::object(['value' => Parameter::int()]);
        
        $this->assertInstanceOf(Parameter::class, $parameter->getParameter('value'));
        $this->assertNull($parameter->getParameter('extra'));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Parameter\ObjectParameter::getParameter
     *
     * @return void
     */
    public function testGetParameterWithAllowedExtraField(): void
    {
        $parameter = Parameter::object(['value' => Parameter::int()])->allowExtraFields();

        $this->assertInstanceOf(Parameter::class, $parameter->getParameter('value'));
        $this->assertInstanceOf(Parameter::class, $parameter->getParameter('extra'));
    }
}
