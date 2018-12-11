<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Entity;

use Chaplean\Bundle\ApiClientBundle\Entity\ApiLog;
use Chaplean\Bundle\ApiClientBundle\Entity\ApiMethodType;
use PHPUnit\Framework\TestCase;

/**
 * Class ApiMethodTypeTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Entity
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class ApiMethodTypeTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiMethodType::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiMethodType::getId()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiMethodType::getKeyname()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiMethodType::setKeyname()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiMethodType::getLogs()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiMethodType::addLog()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiMethodType::removeLog()
     *
     * @return void
     */
    public function testAccessors()
    {
        $apiLog = new ApiLog();
        $method = new ApiMethodType();

        $method->setKeyname('keyname');
        $method->addLog($apiLog);

        $this->assertNull($method->getId());
        $this->assertEquals('keyname', $method->getKeyname());
        $this->assertCount(1, $method->getLogs());

        $method->removeLog($apiLog);

        $this->assertEmpty($method->getLogs());
    }
}
