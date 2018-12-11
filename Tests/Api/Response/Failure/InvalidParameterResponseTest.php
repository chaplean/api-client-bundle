<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Api\Response\Failure;

use Chaplean\Bundle\ApiClientBundle\Api\ParameterConstraintViolationCollection;
use Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\InvalidParameterResponse;
use PHPUnit\Framework\TestCase;

/**
 * Class InvalidParameterResponseTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Api
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class InvalidParameterResponseTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\AbstractFailureResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\InvalidParameterResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\InvalidParameterResponse::getViolations()
     *
     * @return void
     */
    public function testGetViolations()
    {
        $response = new InvalidParameterResponse(new ParameterConstraintViolationCollection(), 'get', 'url', []);
        $violations = $response->getViolations();

        $this->assertInstanceOf(ParameterConstraintViolationCollection::class, $violations);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\AbstractFailureResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\InvalidParameterResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\InvalidParameterResponse::succeeded()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\AbstractFailureResponse::succeeded()
     *
     * @return void
     */
    public function testSucceeded()
    {
        $response = new InvalidParameterResponse(new ParameterConstraintViolationCollection(), 'get', 'url', []);
        $succeeded = $response->succeeded();

        $this->assertFalse($succeeded);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\AbstractFailureResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\InvalidParameterResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\InvalidParameterResponse::getCode()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\AbstractFailureResponse::getCode()
     *
     * @return void
     */
    public function testGetCode()
    {
        $response = new InvalidParameterResponse(new ParameterConstraintViolationCollection(), 'get', 'url', []);

        $this->assertEquals(0, $response->getCode());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\AbstractFailureResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\InvalidParameterResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\InvalidParameterResponse::getContent()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\AbstractFailureResponse::getContent()
     *
     * @return void
     */
    public function testGetContent()
    {
        $response = new InvalidParameterResponse(new ParameterConstraintViolationCollection(), 'get', 'url', []);

        $this->assertNotEmpty($response->getContent());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\AbstractFailureResponse::getMethod()
     *
     * @return void
     */
    public function testGetMethod()
    {
        $response = new InvalidParameterResponse(new ParameterConstraintViolationCollection(), 'get', 'url', []);

        $this->assertEquals('get', $response->getMethod());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\AbstractFailureResponse::getUrl()
     *
     * @return void
     */
    public function testGetUrl()
    {
        $response = new InvalidParameterResponse(new ParameterConstraintViolationCollection(), 'get', 'url', []);

        $this->assertEquals('url', $response->getUrl());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\AbstractFailureResponse::getData()
     *
     * @return void
     */
    public function testGetData()
    {
        $response = new InvalidParameterResponse(new ParameterConstraintViolationCollection(), 'get', 'url', ['key' => 'test']);

        $this->assertEquals(['key' => 'test'], $response->getData());
    }
}
