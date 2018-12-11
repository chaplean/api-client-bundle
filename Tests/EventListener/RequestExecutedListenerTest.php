<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\EventListener;

use Chaplean\Bundle\ApiClientBundle\Api\Response\Success\PlainResponse;
use Chaplean\Bundle\ApiClientBundle\Event\RequestExecutedEvent;
use Chaplean\Bundle\ApiClientBundle\EventListener\RequestExecutedListener;
use Chaplean\Bundle\ApiClientBundle\Utility\EmailUtility;
use Chaplean\Bundle\ApiClientBundle\Utility\ApiLogUtility;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * Class RequestExecutedListenerTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\EventListener
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class RequestExecutedListenerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var ApiLogUtility
     */
    protected $apiLogUtility;

    /**
     * @var EmailUtility
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

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\EventListener\RequestExecutedListener::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\EventListener\RequestExecutedListener::onRequestExecuted()
     *
     * @return void
     */
    public function testCallsLoggersOnRequestExecutedEvent()
    {
        $response = new PlainResponse(new Response(500, [], ''), 'get', 'url', []);

        $this->apiLogUtility->shouldReceive('logResponse')->with($response);
        $this->emailUtility->shouldReceive('sendRequestExecutedNotificationEmail')->with($response);

        $listener = new RequestExecutedListener($this->apiLogUtility, $this->emailUtility);
        $listener->onRequestExecuted(new RequestExecutedEvent($response));
    }
}
