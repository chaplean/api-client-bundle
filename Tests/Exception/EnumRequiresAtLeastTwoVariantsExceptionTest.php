<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Exception;

use Chaplean\Bundle\ApiClientBundle\Exception\EnumRequiresAtLeastTwoVariantsException;
use PHPUnit\Framework\TestCase;

/**
 * Class EnumRequiresAtLeastTwoVariantsExceptionTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Exception
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.1.0
 */
class EnumRequiresAtLeastTwoVariantsExceptionTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Exception\EnumRequiresAtLeastTwoVariantsException::__construct
     *
     * @return void
     */
    public function testExceptionMessage(): void
    {
        $exception = new EnumRequiresAtLeastTwoVariantsException();

        $this->assertSame('Enumeration type require at least two variants', $exception->getMessage());
    }
}
