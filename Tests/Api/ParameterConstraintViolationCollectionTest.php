<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Api;

use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation;
use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection;
use PHPUnit\Framework\TestCase;

/**
 * Class ParameterConstraintViolationCollectionTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Api
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class ParameterConstraintViolationCollectionTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::add()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::next()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::current()
     *
     * @return void
     */
    public function testIterateOnFlatValues()
    {
        $iter = new ParameterConstraintViolationCollection();
        $iter->add('1');
        $iter->add(new ParameterConstraintViolation('2'));
        $iter->add(new ParameterConstraintViolation('3'));

        $actual = iterator_to_array($iter);
        $expected = [
            '0' => new ParameterConstraintViolation('1'),
            '1' => new ParameterConstraintViolation('2'),
            '2' => new ParameterConstraintViolation('3'),
        ];

        $this->assertEquals(array_keys($expected), array_keys($actual));
        $this->assertEquals('1', $actual['0']->getMessage());
        $this->assertEquals('2', $actual['1']->getMessage());
        $this->assertEquals('3', $actual['2']->getMessage());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::add()
     *
     * @expectedException Chaplean\Bundle\ApiClientBundle\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation or a string", "integer" given
     *
     * @return void
     */
    public function testAddInvalidViolation()
    {
        $iter = new ParameterConstraintViolationCollection();
        $iter->add(42);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::isEmpty()
     *
     * @return void
     */
    public function testIsEmptyTrue()
    {
        $iter = new ParameterConstraintViolationCollection();
        $this->assertTrue($iter->isEmpty());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::isEmpty()
     *
     * @return void
     */
    public function testIsEmptyFalse()
    {
        $iter = new ParameterConstraintViolationCollection();
        $iter->add('1');
        $this->assertFalse($iter->isEmpty());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::isEmpty()
     *
     * @return void
     */
    public function testIsEmptyWithEmptyChild()
    {
        $iter = new ParameterConstraintViolationCollection();
        $child = new ParameterConstraintViolationCollection();
        $iter->addChild('child', $child);
        $this->assertTrue($iter->isEmpty());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::isEmpty()
     *
     * @return void
     */
    public function testIsEmptyWithNonEmptyChild()
    {
        $iter = new ParameterConstraintViolationCollection();
        $child = new ParameterConstraintViolationCollection();
        $child->add('1');
        $iter->addChild('child', $child);
        $this->assertFalse($iter->isEmpty());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::addChild()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::merge()
     *
     * @return void
     */
    public function testIterateOnNestedValues()
    {
        $root = new ParameterConstraintViolationCollection();
        $root->add(new ParameterConstraintViolation('1'));
        $root->add(new ParameterConstraintViolation('2'));
        $root->add(new ParameterConstraintViolation('3'));

        $child = new ParameterConstraintViolationCollection();
        $child->add(new ParameterConstraintViolation('4'));
        $child->add(new ParameterConstraintViolation('5'));
        $child->add(new ParameterConstraintViolation('6'));

        $grandChild = new ParameterConstraintViolationCollection();
        $grandChild->add(new ParameterConstraintViolation('7'));

        $child->addChild('grandchild', $grandChild);
        $root->addChild('child', $child);

        $actual = iterator_to_array($root);
        $expected = [
            '0' => new ParameterConstraintViolation('1'),
            '1' => new ParameterConstraintViolation('2'),
            '2' => new ParameterConstraintViolation('3'),
            'child.0' => new ParameterConstraintViolation('4'),
            'child.1' => new ParameterConstraintViolation('5'),
            'child.2' => new ParameterConstraintViolation('6'),
            'child.grandchild.0' => new ParameterConstraintViolation('7'),
        ];

        $this->assertEquals(array_keys($expected), array_keys($actual));
        $this->assertEquals('1', $actual['0']->getMessage());
        $this->assertEquals('2', $actual['1']->getMessage());
        $this->assertEquals('3', $actual['2']->getMessage());
        $this->assertEquals('4', $actual['child.0']->getMessage());
        $this->assertEquals('5', $actual['child.1']->getMessage());
        $this->assertEquals('6', $actual['child.2']->getMessage());
        $this->assertEquals('7', $actual['child.grandchild.0']->getMessage());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::key()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::current()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::next()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::valid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::rewind()
     *
     * @return void
     */
    public function testIterateOnNestedValuesSameChildName()
    {
        $root = new ParameterConstraintViolationCollection();
        $root->add(new ParameterConstraintViolation('1'));
        $root->add(new ParameterConstraintViolation('2'));
        $root->add(new ParameterConstraintViolation('3'));

        $child = new ParameterConstraintViolationCollection();
        $child->add(new ParameterConstraintViolation('4'));
        $child->add(new ParameterConstraintViolation('5'));
        $child->add(new ParameterConstraintViolation('6'));

        $grandChild = new ParameterConstraintViolationCollection();
        $grandChild->add(new ParameterConstraintViolation('7'));

        $root->addChild('child', $child);
        $root->addChild('child', $grandChild);

        $actual = iterator_to_array($root);
        $expected = [
            '0' => new ParameterConstraintViolation('1'),
            '1' => new ParameterConstraintViolation('2'),
            '2' => new ParameterConstraintViolation('3'),
            'child.0' => new ParameterConstraintViolation('4'),
            'child.1' => new ParameterConstraintViolation('5'),
            'child.2' => new ParameterConstraintViolation('6'),
            'child.3' => new ParameterConstraintViolation('7'),
        ];

        $this->assertEquals(array_keys($expected), array_keys($actual));
        $this->assertEquals('1', $actual['0']->getMessage());
        $this->assertEquals('2', $actual['1']->getMessage());
        $this->assertEquals('3', $actual['2']->getMessage());
        $this->assertEquals('4', $actual['child.0']->getMessage());
        $this->assertEquals('5', $actual['child.1']->getMessage());
        $this->assertEquals('6', $actual['child.2']->getMessage());
        $this->assertEquals('7', $actual['child.3']->getMessage());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::key()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::current()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::next()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::valid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::rewind()
     *
     * @return void
     */
    public function testIterateDontAddEmptyNestedValues()
    {
        $root = new ParameterConstraintViolationCollection();
        $root->add(new ParameterConstraintViolation('1'));
        $root->add(new ParameterConstraintViolation('2'));
        $root->add(new ParameterConstraintViolation('3'));

        $child = new ParameterConstraintViolationCollection();

        $root->addChild('child', $child);

        $actual = iterator_to_array($root);
        $expected = [
            '0' => new ParameterConstraintViolation('1'),
            '1' => new ParameterConstraintViolation('2'),
            '2' => new ParameterConstraintViolation('3'),
        ];

        $this->assertEquals(array_keys($expected), array_keys($actual));
        $this->assertEquals('1', $actual['0']->getMessage());
        $this->assertEquals('2', $actual['1']->getMessage());
        $this->assertEquals('3', $actual['2']->getMessage());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection::__toString()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolation::jsonSerialize()
     *
     * @return void
     */
    public function testToString()
    {
        $root = new ParameterConstraintViolationCollection();
        $root->add(new ParameterConstraintViolation('1'));
        $root->add(new ParameterConstraintViolation('2'));
        $root->add(new ParameterConstraintViolation('3'));

        $child = new ParameterConstraintViolationCollection();
        $child->add(new ParameterConstraintViolation('4'));
        $child->add(new ParameterConstraintViolation('5'));
        $child->add(new ParameterConstraintViolation('6'));

        $grandChild = new ParameterConstraintViolationCollection();
        $grandChild->add(new ParameterConstraintViolation('7'));

        $root->addChild('child', $child);
        $root->addChild('child', $grandChild);

        $actual = (string) $root;
        $expected = '{"0":"1","1":"2","2":"3","child.0":"4","child.1":"5","child.2":"6","child.3":"7"}';

        $this->assertEquals($expected, $actual);
    }
}
