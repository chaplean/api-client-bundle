<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Exception;

use Chaplean\Bundle\ApiClientBundle\Exception\LogicException;
use PHPUnit\Framework\TestCase;

/**
 * Class LogicExceptionTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Exception
 * @author    Tom - Chaplean <tom@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class LogicExceptionTest extends TestCase
{
    /**
     * @return void
     */
    public function testExceptionInstance()
    {
        $exception = new LogicException();

        $this->assertInstanceOf('Exception', $exception);
    }
}
