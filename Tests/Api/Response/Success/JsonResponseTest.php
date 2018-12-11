<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Api\Response\Success;

use Chaplean\Bundle\ApiClientBundle\Api\Response\Success\JsonResponse;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class JsonResponseTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Api
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class JsonResponseTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\JsonResponse::getContent()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\AbstractSuccessResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\AbstractSuccessResponse::getContent()
     *
     * @return void
     */
    public function testGetContent()
    {
        $body = file_get_contents(__DIR__ . '/../../../Resources/sample_response.json');
        $response = new JsonResponse(new Response(200, [], $body), 'get', 'url', []);

        $this->assertEquals(
            [
                'value1' => [1, 2],
                'value2' => 42,
                'value3' => 'something with accents éè'
            ],
            $response->getContent()
        );
    }

    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\JsonResponse::getContent()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\AbstractSuccessResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\Response\Success\AbstractSuccessResponse::getContent()
     *
     * @return void
     */
    public function testHandlesLatin1()
    {
        $body = utf8_decode(file_get_contents(__DIR__ . '/../../../Resources/sample_response.json'));
        $response = new JsonResponse(new Response(200, [], $body), 'get', 'url', []);

        $this->assertEquals(
            [
                'value1' => [1, 2],
                'value2' => 42,
                'value3' => 'something with accents éè'
            ],
            $response->getContent()
        );
    }
}
