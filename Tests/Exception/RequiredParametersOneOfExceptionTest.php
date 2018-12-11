<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Exception;

use Chaplean\Bundle\ApiClientBundle\Exception\RequiredParametersOneOfException;
use PHPUnit\Framework\TestCase;

/**
 * Class RequiredParametersOneOfExceptionTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Exception
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 * @coversDefaultClass
 */
class RequiredParametersOneOfExceptionTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Exception\RequiredParametersOneOfException::__construct()
     *
     * @return void
     */
    public function testMessage()
    {
        $violation = new RequiredParametersOneOfException();

        $this->assertEquals(
            'One of required parameters not in original Object',
            $violation->getMessage()
        );
    }
}
