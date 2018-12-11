<?php

namespace Chaplean\Bundle\ApiClientBundle\Tests\Resources;

/**
 * Trait DataProviderTrait.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Tests\Resources
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
trait DataProviderTrait
{
    /**
     * Data provider to test EmailUtility::IsStatusCodeConfiguredForNotifications
     *
     * @return array
     */
    public function statusCodeAndConfigurationForNotificationChecks()
    {
        return [
            '0 - all enabled'    => [0,   ['0', '1XX', '2XX', '3XX', '4XX', '5XX'], true],
            '100 - all enabled'  => [100, ['0', '1XX', '2XX', '3XX', '4XX', '5XX'], true],
            '200 - all enabled'  => [200, ['0', '1XX', '2XX', '3XX', '4XX', '5XX'], true],
            '302 - all enabled'  => [302, ['0', '1XX', '2XX', '3XX', '4XX', '5XX'], true],
            '403 - all enabled'  => [403, ['0', '1XX', '2XX', '3XX', '4XX', '5XX'], true],
            '501 - all enabled'  => [501, ['0', '1XX', '2XX', '3XX', '4XX', '5XX'], true],

            '0 - only ok'        => [0,   ['200'],                                  false],
            '100 - only ok'      => [100, ['200'],                                  false],
            '200 - only ok'      => [200, ['200'],                                  true],
            '302 - only ok'      => [302, ['200'],                                  false],
            '403 - only ok'      => [403, ['200'],                                  false],
            '501 - only ok'      => [501, ['200'],                                  false],

            '0 - only errors'    => [0,   ['0', '4XX', '5XX'],                      true],
            '100 - only errors'  => [100, ['0', '4XX', '5XX'],                      false],
            '200 - only errors'  => [200, ['0', '4XX', '5XX'],                      false],
            '302 - only errors'  => [302, ['0', '4XX', '5XX'],                      false],
            '403 - only errors'  => [403, ['0', '4XX', '5XX'],                      true],
            '501 - only errors'  => [501, ['0', '4XX', '5XX'],                      true],
        ];
    }
}
