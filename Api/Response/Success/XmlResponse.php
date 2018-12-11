<?php

namespace Chaplean\Bundle\ApiClientBundle\Api\Response\Success;

/**
 * Class XmlResponse.
 *
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class XmlResponse extends AbstractSuccessResponse
{
    /**
     * Returns the content of the response to the executed request
     * or the error message if the request failed to execute
     *
     * @return string|array
     */
    public function getContent()
    {
        try {
            $result = simplexml_load_string($this->body);
        } catch (\Exception $e) {
            $result = simplexml_load_string(utf8_encode($this->body));
        }

        return json_decode(json_encode($result), true);
    }
}
