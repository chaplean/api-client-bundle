<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Api;

use Chaplean\Bundle\ApiClientBundle\Api\GlobalParameters;
use Chaplean\Bundle\ApiClientBundle\Api\Parameter;
use Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\AbstractFailureResponse;
use Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\InvalidParameterResponse;
use Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\RequestFailedResponse;
use Chaplean\Bundle\ApiClientBundle\Api\Response\Success\AbstractSuccessResponse;
use Chaplean\Bundle\ApiClientBundle\Api\Response\Success\BinaryResponse;
use Chaplean\Bundle\ApiClientBundle\Api\Response\Success\JsonResponse;
use Chaplean\Bundle\ApiClientBundle\Api\Response\Success\PlainResponse;
use Chaplean\Bundle\ApiClientBundle\Api\Response\Success\XmlResponse;
use Chaplean\Bundle\ApiClientBundle\Api\Route;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RouteTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Api
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class RouteTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var MockInterface|Client
     */
    protected $client;

    /**
     * @var MockInterface|EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->client = \Mockery::mock(Client::class);
        $this->eventDispatcher = \Mockery::mock(EventDispatcherInterface::class);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::__construct()
     *
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function testConstructInvalidArguments()
    {
        new Route('la méthode', 'url', $this->client, $this->eventDispatcher, new GlobalParameters());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::fillInUrlPlaceholders()
     *
     * @return void
     */
    public function testFillInUrlPlaceholdersNoPlaceholder()
    {
        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->client->shouldReceive('request')
            ->once()
            ->with('GET', 'url', ['headers' => [], 'query' => [], 'form_params' => []])
            ->andReturn(new Response());

        $route = new Route(Request::METHOD_GET, '/url', $this->client, $this->eventDispatcher, new GlobalParameters());

        $route->exec();
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::urlParameters()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::bindUrlParameters()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::fillInUrlPlaceholders()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::buildParameter()
     *
     * @return void
     */
    public function testFillInUrlPlaceholdersWithPlaceholders()
    {
        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->client->shouldReceive('request')
            ->once()
            ->with('GET', 'url/with/1/and/2/placeholder', ['headers' => [], 'query' => [], 'form_params' => []])
            ->andReturn(new Response());

        $route = new Route(Request::METHOD_GET, 'url/with/{placeholder}/and/{another}/placeholder', $this->client, $this->eventDispatcher, new GlobalParameters());
        $route->urlParameters(['placeholder' => Parameter::int(), 'another' => Parameter::int()]);
        $route->bindUrlParameters(['placeholder' => 1, 'another' => 2]);

        $route->exec();
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::urlParameters()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::bindUrlParameters()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::fillInUrlPlaceholders()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::buildParameter()
     *
     * @return void
     */
    public function testFillInUrlPlaceholdersWithPlaceholdersComposed()
    {
        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->client->shouldReceive('request')
            ->once()
            ->with('GET', 'url/with/1/and/2.json', ['headers' => [], 'query' => [], 'form_params' => []])
            ->andReturn(new Response());

        $route = new Route(Request::METHOD_GET, 'url/with/{placeholder}/and/{another}.json', $this->client, $this->eventDispatcher, new GlobalParameters());
        $route->urlParameters(['placeholder' => Parameter::int(), 'another' => Parameter::int()]);
        $route->bindUrlParameters(['placeholder' => 1, 'another' => 2]);

        $route->exec();
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::urlParameters()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::fillInUrlPlaceholders()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::buildParameter()
     *
     * @return void
     */
    public function testFillInUrlPlaceholdersWithInvalidData()
    {
        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $route = new Route(Request::METHOD_GET, 'url/with/{placeholder}/and/{another}/placeholder', $this->client, $this->eventDispatcher, new GlobalParameters());
        $route->urlParameters(['placeholder' => Parameter::int(), 'another' => Parameter::int()]);

        $this->assertInstanceOf(InvalidParameterResponse::class, $route->exec());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::headers()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::queryParameters()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::bindHeaders()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::bindQueryParameters()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::buildRequestOptions()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::buildParameter()
     *
     * @return void
     */
    public function testBuildRequestOptions()
    {
        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->client->shouldReceive('request')
            ->once()
            ->with(
                'GET',
                'url',
                [
                    'headers' => [
                        'value1' => 1,
                        'value2' => 2,
                    ],
                    'query'   => [
                        'value3' => 3,
                        'value4' => 4,
                    ],
                    'form_params' => []
                ]
            )
            ->andReturn(new Response());

        $route = new Route(Request::METHOD_GET, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());
        $route->headers(['value1' => Parameter::int(), 'value2' => Parameter::int()]);
        $route->queryParameters(['value3' => Parameter::int(), 'value4' => Parameter::int()]);
        $route->bindHeaders(['value1' => 1, 'value2' => 2]);
        $route->bindQueryParameters(['value3' => 3, 'value4' => 4]);

        $route->exec();
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::headers()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::queryParameters()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::buildParameter()
     *
     * @return void
     */
    public function testBuildRequestOptionsWithInvalidParameters()
    {
        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $route = new Route(Request::METHOD_GET, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());
        $route->headers(['value1' => Parameter::int(), 'value2' => Parameter::int()]);
        $route->queryParameters(['value3' => Parameter::int(), 'value4' => Parameter::int()]);

        $this->assertInstanceOf(InvalidParameterResponse::class, $route->exec());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::headers()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::exec()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::sendRequest()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::getViolations()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::buildParameter()
     *
     * @return void
     */
    public function testExecWithInvalidParameters()
    {
        $this->client->shouldReceive('request')->never();
        $this->eventDispatcher->shouldReceive('dispatch')->once();

        $route = new Route(Request::METHOD_GET, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());
        $route->headers(['value1' => Parameter::int(), 'value2' => Parameter::int()]);

        $response = $route->exec();

        $this->assertInstanceOf(InvalidParameterResponse::class, $response);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::exec()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::sendRequest()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::__construct()
     *
     * @return void
     */
    public function testExec()
    {
        $this->client->shouldReceive('request')->once()->andReturn(new Response());
        $this->eventDispatcher->shouldReceive('dispatch')->once();

        $route = new Route(Request::METHOD_GET, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());

        $response = $route->exec();

        $this->assertInstanceOf(PlainResponse::class, $response);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::expectsJson()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::exec()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::sendRequest()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::__construct()
     *
     * @return void
     */
    public function testExecJson()
    {
        $this->client->shouldReceive('request')->once()->andReturn(new Response());
        $this->eventDispatcher->shouldReceive('dispatch')->once();

        $route = new Route(Request::METHOD_GET, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());
        $route->expectsJson();

        $response = $route->exec();

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::expectsXml()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::exec()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::sendRequest()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::__construct()
     *
     * @return void
     */
    public function testExecXml()
    {
        $this->client->shouldReceive('request')->once()->andReturn(new Response());
        $this->eventDispatcher->shouldReceive('dispatch')->once();

        $route = new Route(Request::METHOD_GET, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());
        $route->expectsXml();

        $response = $route->exec();

        $this->assertInstanceOf(XmlResponse::class, $response);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::expectsXml()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::exec()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::sendRequest()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::__construct()
     *
     * @return void
     */
    public function testExecRequestFailed()
    {
        $this->client->shouldReceive('request')->once()->andThrow(new TransferException());
        $this->eventDispatcher->shouldReceive('dispatch')->once();

        $route = new Route(Request::METHOD_GET, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());
        $route->expectsXml();

        $response = $route->exec();

        $this->assertInstanceOf(RequestFailedResponse::class, $response);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::getUrl()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::__construct()
     *
     * @return void
     */
    public function testGetUrl()
    {
        $route = new Route(Request::METHOD_GET, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());

        $url = $route->getUrl();

        $this->assertEquals('url', $url);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::getUrl()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::getMethod()
     *
     * @return void
     */
    public function testGetMethod()
    {
        $route = new Route(Request::METHOD_GET, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());

        $method = $route->getMethod();

        $this->assertEquals(Request::METHOD_GET, $method);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::expectsPlain()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::__construct()
     *
     * @return void
     */
    public function testExpectsPlain()
    {
        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->client->shouldReceive('request')
            ->once()
            ->with('GET', 'url', ['headers' => [], 'query' => [], 'form_params' => []])
            ->andReturn(new Response());

        $route = new Route(Request::METHOD_GET, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());
        $route->expectsPlain();

        $this->assertInstanceOf(PlainResponse::class, $route->exec());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::expectsBinary()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::__construct()
     *
     * @return void
     */
    public function testExpectsBinary()
    {
        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->client->shouldReceive('request')
            ->once()
            ->with('GET', 'url', ['headers' => [], 'query' => [], 'form_params' => []])
            ->andReturn(new Response());

        $route = new Route(Request::METHOD_GET, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());
        $route->expectsBinary();

        $this->assertInstanceOf(BinaryResponse::class, $route->exec());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::sendRequest()
     *
     * @return void
     */
    public function testRequestSucceedingWithAHttpErrorCodeAreStillSuccessRequest()
    {
        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->client->shouldReceive('request')->once()->andThrow(RequestException::create(new \GuzzleHttp\Psr7\Request('test', 'get'), new Response(500)));
        $route = new Route(Request::METHOD_GET, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());

        $this->assertInstanceOf(AbstractSuccessResponse::class, $route->exec());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::sendRequest()
     *
     * @return void
     */
    public function testRequestWithRequestExceptionAndNoResponse()
    {
        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->client->shouldReceive('request')->once()->andThrow(RequestException::create(new \GuzzleHttp\Psr7\Request('test', 'get')));
        $route = new Route(Request::METHOD_GET, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());

        $this->assertInstanceOf(AbstractFailureResponse::class, $route->exec());
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::requestParameters()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::bindRequestParameters()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::buildParameter()
     *
     * @return void
     */
    public function testBuildRequestOptionsWithPostMethod()
    {
        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->client->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'url',
                [
                    'headers' => [
                        'value1' => 1,
                        'value2' => 2,
                    ],
                    'query'   => [
                        'value3' => 3,
                        'value4' => 4,
                    ],
                    'form_params' => [
                        'value5' => 5,
                        'value6' => 6,
                    ],
                ]
            )
            ->andReturn(new Response());

        $route = new Route(Request::METHOD_POST, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());
        $route->headers(['value1' => Parameter::int(), 'value2' => Parameter::int()]);
        $route->queryParameters(['value3' => Parameter::int(), 'value4' => Parameter::int()]);
        $route->requestParameters(['value5' => Parameter::int(), 'value6' => Parameter::int()]);
        $route->bindHeaders(['value1' => 1, 'value2' => 2]);
        $route->bindQueryParameters(['value3' => 3, 'value4' => 4]);
        $route->bindRequestParameters(['value5' => 5, 'value6' => 6]);

        $route->exec();
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::requestParameters()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::bindRequestParameters()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::sendJson()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::buildParameter()
     *
     * @return void
     */
    public function testBuildRequestOptionsJson()
    {
        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->client->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'url',
                [
                    'headers' => [],
                    'query'   => [],
                    'json'    => [
                        'value' => 42,
                    ],
                ]
            )
            ->andReturn(new Response());

        $route = new Route(Request::METHOD_POST, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());
        $route->requestParameters(['value' => Parameter::int()]);
        $route->sendJson();
        $route->bindRequestParameters(['value' => 42]);

        $route->exec();
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::requestParameters()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::bindRequestParameters()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::sendXml()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::buildParameter()
     *
     * @return void
     */
    public function testBuildRequestOptionsXml()
    {
        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->client->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'url',
                [
                    'headers' => [],
                    'query'   => [],
                    'xml'     => [
                        'value' => 42,
                    ],
                ]
            )
            ->andReturn(new Response());

        $route = new Route(Request::METHOD_POST, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());
        $route->requestParameters(['value' => Parameter::int()]);
        $route->sendXml();
        $route->bindRequestParameters(['value' => 42]);

        $route->exec();
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::sendJSONString()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::buildRequestParameters()
     *
     * @return void
     */
    public function testBuildRequestParametersWithJSONString()
    {
        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->client->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'url',
                [
                    'headers' => [],
                    'query'   => [],
                    'form_params'     => [
                        'JSONString' => '{"value":42}'
                    ],
                ]
            )
            ->andReturn(new Response());

        $route = new Route(Request::METHOD_POST, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());
        $route->requestParameters(['value' => Parameter::int()]);
        $route->sendJSONString();
        $route->bindRequestParameters(['value' => 42]);

        $route->exec();
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::sendFormUrlEncoded()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::buildRequestParameters()
     *
     * @return void
     */
    public function testBuildRequestParametersWithUrlEncoded()
    {
        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->client->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'url',
                [
                    'headers' => [],
                    'query'   => [],
                    'form_params'     => [
                        'value' => 42
                    ],
                ]
            )
            ->andReturn(new Response());

        $route = new Route(Request::METHOD_POST, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());
        $route->requestParameters(['value' => Parameter::int()]);
        $route->sendFormUrlEncoded();
        $route->bindRequestParameters(['value' => 42]);

        $route->exec();
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::buildParameter()
     *
     * @return void
     */
    public function testBuildRequestParametersWithArrayListParameters()
    {
        $this->eventDispatcher->shouldReceive('dispatch')
            ->once();

        $this->client->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'url',
                [
                    'headers' => [],
                    'query'   => [],
                    'json'     => [
                        ['id' => 42]
                    ],
                ]
            )
            ->andReturn(new Response());

        $route = new Route(Request::METHOD_POST, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());
        $route->requestParameters(Parameter::arrayList(Parameter::object(['id' => Parameter::id()])));
        $route->sendJson();
        $route->bindRequestParameters([['id' => 42]]);

        $route->exec();
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::headers()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::urlParameters()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::queryParameters()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::requestParameters()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::buildParameter()
     *
     * @doesNotPerformAssertions
     *
     * @return void
     */
    public function testAnyParameterAreAllowedInDefiningRoutes()
    {
        $route = new Route(Request::METHOD_POST, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());

        $route->headers(Parameter::bool());
        $route->headers(Parameter::int());
        $route->headers(Parameter::string());
        $route->headers(Parameter::dateTime());
        $route->headers(Parameter::id());
        $route->headers(Parameter::untyped());
        $route->headers(Parameter::float());
        $route->headers(Parameter::object([]));
        $route->headers(Parameter::arrayList(Parameter::id()));

        $route->urlParameters(Parameter::bool());
        $route->urlParameters(Parameter::int());
        $route->urlParameters(Parameter::string());
        $route->urlParameters(Parameter::dateTime());
        $route->urlParameters(Parameter::id());
        $route->urlParameters(Parameter::untyped());
        $route->urlParameters(Parameter::float());
        $route->urlParameters(Parameter::object([]));
        $route->urlParameters(Parameter::arrayList(Parameter::id()));

        $route->queryParameters(Parameter::bool());
        $route->queryParameters(Parameter::int());
        $route->queryParameters(Parameter::string());
        $route->queryParameters(Parameter::dateTime());
        $route->queryParameters(Parameter::id());
        $route->queryParameters(Parameter::untyped());
        $route->queryParameters(Parameter::float());
        $route->queryParameters(Parameter::object([]));
        $route->queryParameters(Parameter::arrayList(Parameter::id()));

        $route->requestParameters(Parameter::bool());
        $route->requestParameters(Parameter::int());
        $route->requestParameters(Parameter::string());
        $route->requestParameters(Parameter::dateTime());
        $route->requestParameters(Parameter::id());
        $route->requestParameters(Parameter::untyped());
        $route->requestParameters(Parameter::float());
        $route->requestParameters(Parameter::object([]));
        $route->requestParameters(Parameter::arrayList(Parameter::id()));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::headers()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::buildParameter()
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage You must either provide an array or an instance of Parameter.
     * @return void
     */
    public function testInvalidArgumentsToHeaders()
    {
        $route = new Route(Request::METHOD_POST, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());
        $route->headers(42);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::urlParameters()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::buildParameter()
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage You must either provide an array or an instance of Parameter.
     * @return void
     */
    public function testInvalidArgumentsToUrlParameters()
    {
        $route = new Route(Request::METHOD_POST, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());
        $route->urlParameters(42);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::queryParameters()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::buildParameter()
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage You must either provide an array or an instance of Parameter.
     * @return void
     */
    public function testInvalidArgumentsToQueryParameters()
    {
        $route = new Route(Request::METHOD_POST, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());
        $route->queryParameters(42);
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::requestParameters()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Route::buildParameter()
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage You must either provide an array or an instance of Parameter.
     * @return void
     */
    public function testInvalidArgumentsToRequestParameters()
    {
        $route = new Route(Request::METHOD_POST, 'url', $this->client, $this->eventDispatcher, new GlobalParameters());
        $route->requestParameters(42);
    }
}
