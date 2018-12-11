<?php

namespace Chaplean\Bundle\ApiClientBundle\Api\Response\Success;

/**
 * Class BinaryResponse.
 *
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (http://www.chaplean.coop)
 */
class BinaryResponse extends AbstractSuccessResponse
{
    /**
     * Returns the content of the response to the executed request
     * or the error message if the request failed to execute
     *
     * @return string|array
     */
    public function getContent()
    {
        return $this->body;
    }
}
