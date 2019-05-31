<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Event;

use Chaplean\Bundle\ApiClientBundle\Api\Response\Success\PlainResponse;
use Chaplean\Bundle\ApiClientBundle\Event\RequestExecutedEvent;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class RequestExecutedEventTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Event
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class RequestExecutedEventTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Event\RequestExecutedEvent::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Event\RequestExecutedEvent::getResponse()
     * @covers \Chaplean\Bundle\ApiClientBundle\Event\RequestExecutedEvent::getApiName()
     *
     * @return void
     */
    public function testConstruct()
    {
        $response = new PlainResponse(new Response(200, [], ''), 'get', 'url', []);
        $event = new RequestExecutedEvent($response, 'bar_api');

        $this->assertEquals($response, $event->getResponse());
        $this->assertSame('bar_api', $event->getApiName());
    }
}
