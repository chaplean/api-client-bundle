<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\EventListener;

use Chaplean\Bundle\ApiClientBundle\Api\Response\Success\PlainResponse;
use Chaplean\Bundle\ApiClientBundle\Event\RequestExecutedEvent;
use Chaplean\Bundle\ApiClientBundle\EventListener\RequestExecutedListener;
use Chaplean\Bundle\ApiClientBundle\Utility\EmailUtility;
use Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;

/**
 * Class RequestExecutedListenerTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\EventListener
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class RequestExecutedListenerTest extends MockeryTestCase
{
    /**
     * @var ApiLogUtility|MockInterface
     */
    protected $apiLogUtility;

    /**
     * @var EmailUtility|MockInterface
     */
    protected $emailUtility;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->apiLogUtility = \Mockery::mock(ApiLogUtility::class);
        $this->emailUtility = \Mockery::mock(EmailUtility::class);
    }
//
//    /**
//     * @covers \Chaplean\Bundle\ApiClientBundle\EventListener\RequestExecutedListener::__construct()
//     * @covers \Chaplean\Bundle\ApiClientBundle\EventListener\RequestExecutedListener::onRequestExecuted()
//     *
//     * @return void
//     */
//    public function testCallsLoggersOnRequestExecutedEvent()
//    {
//        $response = new PlainResponse(new Response(500, [], ''), 'get', 'url', []);
//
//        $this->apiLogUtility->shouldReceive('logResponse')->with($response);
//        $this->emailUtility->shouldReceive('sendRequestExecutedNotificationEmail')->with($response);
//
//        $listener = new RequestExecutedListener([], $this->apiLogUtility, $this->emailUtility);
//        $listener->onRequestExecuted(new RequestExecutedEvent($response, 'bar_api'));
//    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\EventListener\RequestExecutedListener::__construct
     * @covers \Chaplean\Bundle\ApiClientBundle\EventListener\RequestExecutedListener::isEnabledLoggingFor
     *
     * @return void
     */
    public function testLoggingIsEnabledExplicitDefinition()
    {
        $listener = new RequestExecutedListener([
            'enable_database_logging' => [
                'type' => 'inclusive',
                'elements' => [
                    'bar_api'
                ]
            ]
        ], $this->apiLogUtility, $this->emailUtility);

        $this->assertTrue($listener->isEnabledLoggingFor('enable_database_logging', 'bar_api'));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\EventListener\RequestExecutedListener::isEnabledLoggingFor
     *
     * @return void
     */
    public function testLoggingIsEnabledTildDefinition()
    {
        $listener = new RequestExecutedListener([
            'enable_database_logging' => null
        ], $this->apiLogUtility, $this->emailUtility);

        $this->assertTrue($listener->isEnabledLoggingFor('enable_database_logging', 'bar_api'));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\EventListener\RequestExecutedListener::isEnabledLoggingFor
     *
     * @return void
     */
    public function testLoggingIsEnabledExclusiveDefinition()
    {
        $listener = new RequestExecutedListener([
            'enable_database_logging' => [
                'type' => 'exclusive',
                'elements' => [
                    'foo_api'
                ]
            ]
        ], $this->apiLogUtility, $this->emailUtility);

        $this->assertTrue($listener->isEnabledLoggingFor('enable_database_logging', 'bar_api'));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\EventListener\RequestExecutedListener::isEnabledLoggingFor
     *
     * @return void
     */
    public function testLoggingIsDisabledExplicitDefinition()
    {
        $listener = new RequestExecutedListener([
            'enable_database_logging' => [
                'type' => 'exclusive',
                'elements' => [
                    'bar_api'
                ]
            ]
        ], $this->apiLogUtility, $this->emailUtility);

        $this->assertFalse($listener->isEnabledLoggingFor('enable_database_logging', 'bar_api'));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\EventListener\RequestExecutedListener::isEnabledLoggingFor
     *
     * @return void
     */
    public function testLoggingIsDisabledByDefault()
    {
        $listener = new RequestExecutedListener([], $this->apiLogUtility, $this->emailUtility);

        $this->assertFalse($listener->isEnabledLoggingFor('enable_database_logging', 'bar_api'));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\EventListener\RequestExecutedListener::isEnabledLoggingFor
     *
     * @return void
     */
    public function testLoggingIsDisabledNotDefineApiName()
    {
        $listener = new RequestExecutedListener([
            'enable_database_logging' => [
                'type' => 'inclusive',
                'elements' => [
                    'foo_api'
                ]
            ]
        ], $this->apiLogUtility, $this->emailUtility);

        $this->assertFalse($listener->isEnabledLoggingFor('enable_database_logging', 'bar_api'));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\EventListener\RequestExecutedListener::onRequestExecuted
     *
     * @return void
     */
    public function testOnRequestExecutedWithBothLoggingEnabled()
    {
        $response = new PlainResponse(new Response(500, [], ''), 'get', 'url', []);

        $listener = new RequestExecutedListener([
            'enable_database_logging' => [
                'type' => 'inclusive',
                'elements' => [
                    'bar_api'
                ]
            ],
            'enable_email_logging' => [
                'type' => 'inclusive',
                'elements' => [
                    'bar_api'
                ]
            ],
        ], $this->apiLogUtility, $this->emailUtility);

        $this->apiLogUtility->shouldReceive('logResponse')->once()->with($response);
        $this->emailUtility->shouldReceive('sendRequestExecutedNotificationEmail')->once()->with($response);

        $listener->onRequestExecuted(new RequestExecutedEvent($response, 'bar_api'));
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\EventListener\RequestExecutedListener::onRequestExecuted
     *
     * @return void
     */
    public function testOnRequestExecutedWithBothLoggingDisabled()
    {
        $response = new PlainResponse(new Response(500, [], ''), 'get', 'url', []);

        $listener = new RequestExecutedListener([], $this->apiLogUtility, $this->emailUtility);

        $this->apiLogUtility->shouldNotReceive('logResponse');
        $this->emailUtility->shouldNotReceive('sendRequestExecutedNotificationEmail');

        $listener->onRequestExecuted(new RequestExecutedEvent($response, 'bar_api'));
    }
}
