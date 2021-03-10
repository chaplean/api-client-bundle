<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Api;

use Chaplean\Bundle\ApiClientBundle\Api\Route;
use Chaplean\Bundle\ApiClientBundle\Tests\Resources\Api\TestApi;
use Chaplean\Bundle\ApiClientBundle\Tests\Resources\Api\WithoutGlobalParametersTestApi;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class AbstractApiTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Api
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class AbstractApiTest extends TestCase
{
    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->eventDispatcher = \Mockery::mock(EventDispatcherInterface::class);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\AbstractApi::__call
     *
     * @expectedException \Exception
     * @expectedExceptionMessage invalidCall is invalid, it should start with a HTTP verb
     *
     * @return void
     */
    public function testInvalidCall()
    {
        $api = new TestApi(new Client(), $this->eventDispatcher);
        $api->invalidCall();
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\AbstractApi::globalParameters()
     *
     * @expectedException \LogicException
     * @expectedExceptionMessage globalParameters() must be called before any route definition
     *
     * @return void
     */
    public function testGlobalParametersCalledAfterRouteDefined()
    {
        $api = new WithoutGlobalParametersTestApi(new Client(), $this->eventDispatcher);
        $api->globalParameters();
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\AbstractApi::globalParameters()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\AbstractApi::get()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\AbstractApi::post()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\AbstractApi::put()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\AbstractApi::patch()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\AbstractApi::delete()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\AbstractApi::addRoute()
     *
     * @return void
     */
    public function testGlobalParametersCalledBeforeRouteDefined()
    {
        $api = new TestApi(new Client(), $this->eventDispatcher);

        $this->assertInstanceOf(Route::class, $api->getGet());
        $this->assertInstanceOf(Route::class, $api->getGet2());
        $this->assertInstanceOf(Route::class, $api->postPost());
        $this->assertInstanceOf(Route::class, $api->putPut());
        $this->assertInstanceOf(Route::class, $api->patchPatch());
        $this->assertInstanceOf(Route::class, $api->deleteDelete());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\AbstractApi::getName
     *
     * @return void
     */
    public function testGetName()
    {
        $api = new TestApi(new Client(), $this->eventDispatcher);

        $this->assertSame('test_api', $api->getName());
    }
}
