<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Api;

use Chaplean\Bundle\ApiClientBundle\Api\AbstractResponse;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractResponseTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Api
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class AbstractResponseTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\AbstractResponse::__construct()
     * @covers \Chaplean\Bundle\ApiClientBundle\Api\AbstractResponse::getUuid()
     *
     * @return void
     */
    public function testGetUuid()
    {
        /** @var AbstractResponse $abstractResponse */
        $abstractResponse = \Mockery::mock(AbstractResponse::class, [])->makePartial();

        $this->assertNotNull($abstractResponse->getUuid());
    }
}
