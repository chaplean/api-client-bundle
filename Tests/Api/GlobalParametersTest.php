<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Api;

use Chaplean\Bundle\ApiClientBundle\Api\GlobalParameters;
use Chaplean\Bundle\ApiClientBundle\Api\Parameter;
use Chaplean\Bundle\ApiClientBundle\Api\Route;
use Chaplean\Bundle\ApiClientBundle\Api\Response\Success\BinaryResponse;
use Chaplean\Bundle\ApiClientBundle\Api\Response\Success\JsonResponse;
use Chaplean\Bundle\ApiClientBundle\Api\Response\Success\PlainResponse;
use Chaplean\Bundle\ApiClientBundle\Api\Response\Success\XmlResponse;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class GlobalParametersTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Api
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class GlobalParametersTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var ClientInterface|MockInterface
     */
    protected $client;

    /**
     * @var EventDispatcherInterface|MockInterface
     */
    protected $eventDispatcher;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->client = \Mockery::mock(ClientInterface::class);
        $this->eventDispatcher = \Mockery::mock(EventDispatcherInterface::class);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\GlobalParameters::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\GlobalParameters::expectsPlain()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\GlobalParameters::sendFormUrlEncoded()
     *
     * @return void
     */
    public function testGlobalParametersConstruct()
    {
        $globalParameter = new GlobalParameters();
        $route = new Route('POST', 'url', $this->client, $this->eventDispatcher, $globalParameter, 'foo_api');

        $this->client->shouldReceive('request')
            ->once()
            ->with('POST', 'url', ['headers' => [], 'query' => [], 'form_params' => []])
            ->andReturn(new Response());

        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->assertInstanceOf(PlainResponse::class, $route->exec());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\GlobalParameters::expectsPlain()
     *
     * @return void
     */
    public function testGlobalParametersExpectBinary()
    {
        $globalParameter = new GlobalParameters();
        $globalParameter->expectsBinary();
        $route = new Route('POST', 'url', $this->client, $this->eventDispatcher, $globalParameter, 'foo_api');

        $this->client->shouldReceive('request')
            ->once()
            ->with('POST', 'url', ['headers' => [], 'query' => [], 'form_params' => []])
            ->andReturn(new Response());

        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->assertInstanceOf(BinaryResponse::class, $route->exec());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\GlobalParameters::expectsJson()
     *
     * @return void
     */
    public function testGlobalParametersExpectJson()
    {
        $globalParameter = new GlobalParameters();
        $globalParameter->expectsJson();
        $route = new Route('POST', 'url', $this->client, $this->eventDispatcher, $globalParameter, 'foo_api');

        $this->client->shouldReceive('request')
            ->once()
            ->with('POST', 'url', ['headers' => [], 'query' => [], 'form_params' => []])
            ->andReturn(new Response());

        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->assertInstanceOf(JsonResponse::class, $route->exec());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\GlobalParameters::expectsXml()
     *
     * @return void
     */
    public function testGlobalParametersExpectXml()
    {
        $globalParameter = new GlobalParameters();
        $globalParameter->expectsXml();
        $route = new Route('POST', 'url', $this->client, $this->eventDispatcher, $globalParameter, 'foo_api');

        $this->client->shouldReceive('request')
            ->once()
            ->with('POST', 'url', ['headers' => [], 'query' => [], 'form_params' => []])
            ->andReturn(new Response());

        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->assertInstanceOf(XmlResponse::class, $route->exec());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\GlobalParameters::sendJson()
     *
     * @return void
     */
    public function testGlobalParametersSendJson()
    {
        $globalParameter = new GlobalParameters();
        $globalParameter->sendJson();

        $route = new Route('POST', 'url', $this->client, $this->eventDispatcher, $globalParameter, 'foo_api');

        $this->client->shouldReceive('request')
            ->once()
            ->with('POST', 'url', ['headers' => [], 'query' => [], 'json' => []])
            ->andReturn(new Response());

        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->assertInstanceOf(PlainResponse::class, $route->exec());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\GlobalParameters::sendXml()
     *
     * @return void
     */
    public function testGlobalParametersSendXml()
    {
        $globalParameter = new GlobalParameters();
        $globalParameter->sendXml();

        $route = new Route('POST', 'url', $this->client, $this->eventDispatcher, $globalParameter, 'foo_api');

        $this->client->shouldReceive('request')
            ->once()
            ->with('POST', 'url', ['headers' => [], 'query' => [], 'xml' => []])
            ->andReturn(new Response());

        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->assertInstanceOf(PlainResponse::class, $route->exec());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\GlobalParameters::sendJSONString()
     *
     * @return void
     */
    public function testGlobalParametersSendJSONString()
    {
        $globalParameter = new GlobalParameters();
        $globalParameter->sendJSONString();

        $route = new Route('POST', 'url', $this->client, $this->eventDispatcher, $globalParameter, 'foo_api');

        $this->client->shouldReceive('request')
            ->once()
            ->with('POST', 'url', ['headers' => [], 'query' => [], 'form_params' => ['JSONString' => '[]']])
            ->andReturn(new Response());

        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->assertInstanceOf(PlainResponse::class, $route->exec());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\GlobalParameters::urlPrefix()
     *
     * @return void
     */
    public function testGlobalParametersUrlPrefix()
    {
        $globalParameter = new GlobalParameters();
        $globalParameter->urlPrefix('prefix');

        $route = new Route('POST', 'url', $this->client, $this->eventDispatcher, $globalParameter, 'foo_api');

        $this->client->shouldReceive('request')
            ->once()
            ->with('POST', 'prefixurl', ['headers' => [], 'query' => [], 'form_params' => []])
            ->andReturn(new Response());

        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->assertInstanceOf(PlainResponse::class, $route->exec());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\GlobalParameters::urlSuffix()
     *
     * @return void
     */
    public function testGlobalParametersUrlSuffix()
    {
        $globalParameter = new GlobalParameters();
        $globalParameter->urlSuffix('suffix');

        $route = new Route('POST', 'url', $this->client, $this->eventDispatcher, $globalParameter, 'foo_api');

        $this->client->shouldReceive('request')
            ->once()
            ->with('POST', 'urlsuffix', ['headers' => [], 'query' => [], 'form_params' => []])
            ->andReturn(new Response());

        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->assertInstanceOf(PlainResponse::class, $route->exec());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\GlobalParameters::urlParameters()
     *
     * @return void
     */
    public function testGlobalParametersUrlParameters()
    {
        $globalParameter = new GlobalParameters();
        $globalParameter->urlParameters(['id' => Parameter::id()]);

        $route = new Route('POST', 'url/{id}', $this->client, $this->eventDispatcher, $globalParameter, 'foo_api');
        $route->bindUrlParameters(['id' => 42]);

        $this->client->shouldReceive('request')
            ->once()
            ->with('POST', 'url/42', ['headers' => [], 'query' => [], 'form_params' => []])
            ->andReturn(new Response());

        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->assertInstanceOf(PlainResponse::class, $route->exec());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\GlobalParameters::queryParameters()
     *
     * @return void
     */
    public function testGlobalParametersQueryParameters()
    {
        $globalParameter = new GlobalParameters();
        $globalParameter->queryParameters(['id' => Parameter::id()]);

        $route = new Route('POST', 'url', $this->client, $this->eventDispatcher, $globalParameter, 'foo_api');
        $route->bindQueryParameters(['id' => 42]);

        $this->client->shouldReceive('request')
            ->once()
            ->with('POST', 'url', ['headers' => [], 'query' => ['id' => 42], 'form_params' => []])
            ->andReturn(new Response());

        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->assertInstanceOf(PlainResponse::class, $route->exec());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\GlobalParameters::headers()
     *
     * @return void
     */
    public function testGlobalParametersHeaders()
    {
        $globalParameter = new GlobalParameters();
        $globalParameter->headers(['id' => Parameter::id()]);

        $route = new Route('POST', 'url', $this->client, $this->eventDispatcher, $globalParameter, 'foo_api');
        $route->bindHeaders(['id' => 42]);

        $this->client->shouldReceive('request')
            ->once()
            ->with('POST', 'url', ['headers' => ['id' => 42], 'query' => [], 'form_params' => []])
            ->andReturn(new Response());

        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->assertInstanceOf(PlainResponse::class, $route->exec());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\GlobalParameters::requestParameters()
     *
     * @return void
     */
    public function testGlobalParametersRequestParameters()
    {
        $globalParameter = new GlobalParameters();
        $globalParameter->requestParameters(['id' => Parameter::id()]);

        $route = new Route('POST', 'url', $this->client, $this->eventDispatcher, $globalParameter, 'foo_api');
        $route->bindRequestParameters(['id' => 42]);

        $this->client->shouldReceive('request')
            ->once()
            ->with('POST', 'url', ['headers' => [], 'query' => [], 'form_params' => ['id' => 42]])
            ->andReturn(new Response());

        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->assertInstanceOf(PlainResponse::class, $route->exec());
    }
}
