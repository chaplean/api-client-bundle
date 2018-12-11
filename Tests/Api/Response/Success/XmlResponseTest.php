<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Api\Response\Success;

use Chaplean\Bundle\ApiClientBundle\Api\Response\Success\XmlResponse;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class XmlResponseTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Api
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http:/.chaplean.coop)
 * @since     1.0.0
 */
class XmlResponseTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\XmlResponse::getContent()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\AbstractSuccessResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\AbstractSuccessResponse::getContent()
     *
     * @return void
     */
    public function testGetContent()
    {
        $body = file_get_contents(__DIR__ . '/../../../Resources/sample_response.xml');
        $response = new XmlResponse(new Response(200, [], $body), 'get', 'url', []);

        $this->assertEquals(
            [
                'value2' => 'something with accents éè',
                'list' => ['element' => [1, 2]],
                '@attributes' => ['attribute' => 'something']
            ],
            $response->getContent()
        );
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\XmlResponse::getContent()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\AbstractSuccessResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\AbstractSuccessResponse::getContent()
     *
     * @return void
     */
    public function testHandlesLatin1()
    {
        $body = utf8_decode(file_get_contents(__DIR__ . '/../../../Resources/sample_response.xml'));
        $response = new XmlResponse(new Response(200, [], $body), 'get', 'url', []);

        $this->assertEquals(
            [
                'value2' => 'something with accents éè',
                'list' => ['element' => [1, 2]],
                '@attributes' => ['attribute' => 'something']
            ],
            $response->getContent()
        );
    }
}
