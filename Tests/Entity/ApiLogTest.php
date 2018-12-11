<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Entity;

use Chaplean\Bundle\ApiClientBundle\Entity\ApiLog;
use Chaplean\Bundle\ApiClientBundle\Entity\ApiMethodType;
use Chaplean\Bundle\ApiClientBundle\Entity\ApiStatusCodeType;
use PHPUnit\Framework\TestCase;

/**
 * Class ApiLogTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Entity
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class ApiLogTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiLog::getId()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiLog::getUrl()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiLog::setUrl()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiLog::getDataSended()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiLog::setDataSended()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiLog::getDataReceived()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiLog::setDataReceived()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiLog::getDateAdd()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiLog::setDateAdd()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiLog::getMethod()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiLog::setMethod()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiLog::getStatusCode()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiLog::setStatusCode()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiLog::getResponseUuid()
     * @covers \Chaplean\Bundle\ApiClientBundle\Entity\ApiLog::setResponseUuid()
     *
     * @return void
     */
    public function testAccessors()
    {
        $log = new ApiLog();
        $method = new ApiMethodType();
        $statusCode = new ApiStatusCodeType();

        $log->setUrl('url');
        $log->setDataSended(['data' => 'sended']);
        $log->setDataReceived(['data' => 'received']);
        $log->setDateAdd(new \DateTime('today'));
        $log->setMethod($method);
        $log->setStatusCode($statusCode);
        $log->setResponseUuid('foo');

        $this->assertNull($log->getId());
        $this->assertEquals('url', $log->getUrl());
        $this->assertEquals(['data' => 'sended'], $log->getDataSended());
        $this->assertEquals(['data' => 'received'], $log->getDataReceived());
        $this->assertEquals(new \DateTime('today'), $log->getDateAdd());
        $this->assertEquals($method, $log->getMethod());
        $this->assertEquals($statusCode, $log->getStatusCode());
        $this->assertEquals('foo', $log->getResponseUuid());
    }
}
