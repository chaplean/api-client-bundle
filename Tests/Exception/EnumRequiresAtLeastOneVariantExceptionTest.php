<?php

namespace Tests\Chaplean\Bundle\ApiClientBundle\Exception;

use Chaplean\Bundle\ApiClientBundle\Exception\EnumRequiresAtLeastOneVariantException;
use PHPUnit\Framework\TestCase;

/**
 * Class EnumRequiresAtLeastOneVariantExceptionTest.
 *
 * @package   Tests\Chaplean\Bundle\ApiClientBundle\Exception
 * @author    Valentin - Chaplean <valentin@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.1.0
 */
class EnumRequiresAtLeastOneVariantExceptionTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\ApiClientBundle\Exception\EnumRequiresAtLeastOneVariantException::__construct
     *
     * @return void
     */
    public function testExceptionMessage(): void
    {
        $exception = new EnumRequiresAtLeastOneVariantException();

        $this->assertSame('Enumeration type require at least one variant', $exception->getMessage());
    }
}
