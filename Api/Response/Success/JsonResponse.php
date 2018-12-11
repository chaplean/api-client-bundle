<?php

namespace Chaplean\Bundle\ApiClientBundle\Api\Response\Success;

/**
 * Class JsonResponse.
 *
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class JsonResponse extends AbstractSuccessResponse
{
    /**
     * Returns the content of the response to the executed request
     * or the error message if the request failed to execute
     *
     * @return string|array
     */
    public function getContent()
    {
        $result = json_decode($this->body, true);

        if ($result === null) {
            $result = json_decode(utf8_encode($this->body), true);
        }

        return $result;
    }
}
