<?php

namespace Chaplean\Bundle\ApiClientBundle\Tests\Resources\Api;

use Chaplean\Bundle\ApiClientBundle\Api\AbstractApi;

/**
 * Class TestApi.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Tests\Resources
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class TestApi extends AbstractApi
{
    /**
     * @return void
     */
    public function buildApi()
    {
        $this->globalParameters();
        $this->get('get', 'get');
        $this->get('get2', 'get2');
        $this->post('post', 'post');
        $this->put('put', 'put');
        $this->patch('patch', 'patch');
        $this->delete('delete', 'delete');
    }
}
