<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Api\Response\Success;

use Chaplean\Bundle\ApiClientBundle\Api\Response\Success\PlainResponse;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class PlainResponseTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Api
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class PlainResponseTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\PlainResponse::getContent()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\AbstractSuccessResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\AbstractSuccessResponse::getContent()
     *
     * @return void
     */
    public function testGetContent()
    {
        $response = new PlainResponse(new Response(200, [], 'some content'), 'get', 'url', []);

        $this->assertEquals('some content', $response->getContent());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\AbstractSuccessResponse::succeeded()
     *
     * @return void
     */
    public function testSucceeded()
    {
        $response = new PlainResponse(new Response(200, [], 'some content'), 'get', 'url', []);

        $this->assertTrue($response->succeeded());

        $response = new PlainResponse(new Response(400, [], 'some content'), 'get', 'url', []);

        $this->assertFalse($response->succeeded());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\AbstractSuccessResponse::getCode()
     *
     * @return void
     */
    public function testGetCode()
    {
        $response = new PlainResponse(new Response(200, [], 'some content'), 'get', 'url', []);

        $this->assertEquals(200, $response->getCode());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\AbstractSuccessResponse::getViolations()
     *
     * @return void
     */
    public function testGetViolation()
    {
        $response = new PlainResponse(new Response(200, [], 'some content'), 'get', 'url', []);

        $this->assertNull($response->getViolations());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\AbstractSuccessResponse::getMethod()
     *
     * @return void
     */
    public function testGetMethod()
    {
        $response = new PlainResponse(new Response(200, [], 'some content'), 'get', 'url', []);

        $this->assertEquals('get', $response->getMethod());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\AbstractSuccessResponse::getUrl()
     *
     * @return void
     */
    public function testGetUrl()
    {
        $response = new PlainResponse(new Response(200, [], 'some content'), 'get', 'url', []);

        $this->assertEquals('url', $response->getUrl());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\AbstractSuccessResponse::getData()
     *
     * @return void
     */
    public function testGetData()
    {
        $response = new PlainResponse(new Response(200, [], 'some content'), 'get', 'url', ['key' => 'test']);

        $this->assertEquals(['key' => 'test'], $response->getData());
    }
}
