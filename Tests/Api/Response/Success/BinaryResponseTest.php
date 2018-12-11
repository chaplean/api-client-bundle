<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Api\Response\Success;

use Chaplean\Bundle\ApiClientBundle\Api\Response\Success\BinaryResponse;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class BinaryResponseTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Api
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 */
class BinaryResponseTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\BinaryResponse::getContent()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\AbstractSuccessResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\AbstractSuccessResponse::getContent()
     *
     * @return void
     */
    public function testGetContent()
    {
        $response = new BinaryResponse(new Response(200, [], 'some content'), 'get', 'url', []);

        $this->assertEquals('some content', $response->getContent());
    }
}
