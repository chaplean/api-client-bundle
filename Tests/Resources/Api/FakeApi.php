<?php

namespace Chaplean\Bundle\ApiClientBundle\Tests\Resources\Api;

use Chaplean\Bundle\ApiClientBundle\Api\AbstractApi;
use Chaplean\Bundle\ApiClientBundle\Api\Parameter;

/**
 * Class FakeApi.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Api
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class FakeApi extends AbstractApi
{
    protected $url = '127.0.0.1:80/app_test.php/';
    protected $token = 'random_token';

    /**
     * @return void
     */
    public function buildApi()
    {
        $this->globalParameters()
            ->urlPrefix($this->url);
//            ->queryParameters([
//                'access_token' => Parameter::String()->defaultValue($this->token),
//            ])
//            ->headers([
//                'Custom-Header' => Parameter::String()->optional(),
//            ]);

        $this->get('fake_get_plain', 'fake/get.json');

        $this->get('fake_get_json', 'fake/get.json')
            ->expectsJson();

        $this->get('fake_get_xml', 'fake/get.xml')
            ->expectsXml();

        $this->get('name', 'url/with/{placeholder}')
            ->urlParameters([
                'placeholder' => Parameter::id(),
            ])
            ->headers([
                'If-Modified-Since' => Parameter::dateTime()
            ]);

        $this->get('name', 'url/with/query/strings')
            ->queryParameters([
                'page'    => Parameter::int()/*->unsigned()*/->optional(),
                'limit'   => Parameter::int()/*->minimum(10)->maximum(100)*/->optional(),
                'options' => Parameter::arrayList(Parameter::string()/*->values(['option1', 'option2', 'option3'])*/)->optional(),
            ]);

        $this->get('name_of_cars', 'url/with/query/strings')
            ->queryParameters(
                [
                    'marque' => Parameter::string()
                        ->defaultValue('audi')
                ]
            );

        $this->post('name', 'url')
            ->requestParameters([
                'object' => Parameter::object([
                    'name' => Parameter::id()
                ]),
                'array' => Parameter::arrayList(Parameter::id())
            ]);

        $this->post('bonuses', 'bonuses')
            ->queryParameters(
                [
                    'access_token'   => Parameter::string()->defaultValue('token'),
                    'amount'          => Parameter::int(),
                    'reason'     => Parameter::string(),
                    'receiver_email'       => Parameter::string()
                ]
            );

        $this->put('name', 'url/{id}')
            ->urlParameters([
                'id' => Parameter::id(),
            ])
            ->requestParameters([
                'object' => Parameter::object([
                    'name' => Parameter::id()
                ]),
                'array' => Parameter::arrayList(Parameter::id())
            ]);

        $this->patch('name', 'url/{id}')
            ->urlParameters([
                'id' => Parameter::id(),
            ])
            ->requestParameters([
                'object' => Parameter::object([
                    'name' => Parameter::id()
                ]),
                'array' => Parameter::arrayList(Parameter::id())
            ]);

        $this->delete('name', 'url/{id}')
            ->urlParameters([
                'id' => Parameter::id(),
            ]);
    }
}
