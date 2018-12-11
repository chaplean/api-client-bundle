<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Api\Response\Failure;

use Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\RequestFailedResponse;
use GuzzleHttp\Exception\TransferException;
use PHPUnit\Framework\TestCase;

/**
 * Class RequestFailedResponseTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Api
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class RequestFailedResponseTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\AbstractFailureResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\RequestFailedResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\RequestFailedResponse::getContent()
     *
     * @return void
     */
    public function testGetContent()
    {
        $response = new RequestFailedResponse(new TransferException('message'), 'get', 'url', []);
        $content = $response->getContent();

        $this->assertEquals('message', $content);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\AbstractFailureResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\RequestFailedResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\AbstractFailureResponse::succeeded()
     *
     * @return void
     */
    public function testSucceeded()
    {
        $response = new RequestFailedResponse(new TransferException('message'), 'get', 'url', []);
        $succeeded = $response->succeeded();

        $this->assertFalse($succeeded);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\AbstractFailureResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\RequestFailedResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\AbstractFailureResponse::getCode()
     *
     * @return void
     */
    public function testGetCode()
    {
        $response = new RequestFailedResponse(new TransferException('message'), 'get', 'url', []);

        $this->assertEquals(0, $response->getCode());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\AbstractFailureResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\RequestFailedResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\AbstractFailureResponse::getViolations()
     *
     * @return void
     */
    public function testGetViolations()
    {
        $response = new RequestFailedResponse(new TransferException('message'), 'get', 'url', []);

        $this->assertNull($response->getViolations());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\AbstractFailureResponse::getMethod()
     *
     * @return void
     */
    public function testGetMethod()
    {
        $response = new RequestFailedResponse(new TransferException('message'), 'get', 'url', []);

        $this->assertEquals('get', $response->getMethod());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\AbstractFailureResponse::getUrl()
     *
     * @return void
     */
    public function testGetUrl()
    {
        $response = new RequestFailedResponse(new TransferException('message'), 'get', 'url', []);

        $this->assertEquals('url', $response->getUrl());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\AbstractFailureResponse::getData()
     *
     * @return void
     */
    public function testGetData()
    {
        $response = new RequestFailedResponse(new TransferException('message'), 'get', 'url', ['key' => 'test']);

        $this->assertEquals(['key' => 'test'], $response->getData());
    }
}
