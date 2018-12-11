<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Entity;

use Chaplean\Bundle\ApiClientBundle\Entity\ApiLog;
use Chaplean\Bundle\ApiClientBundle\Entity\ApiStatusCodeType;
use PHPUnit\Framework\TestCase;

/**
 * Class ApiStatusCodeTypeTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Entity
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class ApiStatusCodeTypeTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiStatusCodeType::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiStatusCodeType::getId()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiStatusCodeType::getCode()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiStatusCodeType::setCode()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiStatusCodeType::getKeyname()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiStatusCodeType::setKeyname()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiStatusCodeType::getLogs()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiStatusCodeType::addLog()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiStatusCodeType::removeLog()
     *
     * @return void
     */
    public function testAccessors()
    {
        $apiLog = new ApiLog();
        $statusCode = new ApiStatusCodeType();

        $statusCode->setCode(418);
        $statusCode->setKeyname('keyname');
        $statusCode->addLog($apiLog);

        $this->assertNull($statusCode->getId());
        $this->assertEquals(418, $statusCode->getCode());
        $this->assertEquals('keyname', $statusCode->getKeyname());
        $this->assertCount(1, $statusCode->getLogs());

        $statusCode->removeLog($apiLog);

        $this->assertEmpty($statusCode->getLogs());
    }
}
