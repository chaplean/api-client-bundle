<?php

namespace Chaplean\Bundle\ApiClientBundle\Tests\Resources\Api;

use Chaplean\Bundle\ApiClientBundle\Api\AbstractApi;

/**
 * Class WithoutGlobalParametersTestApi.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Tests\Resources
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class WithoutGlobalParametersTestApi extends AbstractApi
{
    /**
     * @return void
     */
    public function buildApi()
    {
        $this->get('test', 'test');
    }
}
