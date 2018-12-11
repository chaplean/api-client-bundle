<?php

namespace Chaplean\Bundle\ApiClientBundle\Api\Response\Success;

/**
 * Class PlainResponse.
 *
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class PlainResponse extends AbstractSuccessResponse
{
    /**
     * Returns the content of the response to the executed request
     * or the error message if the request failed to execute
     *
     * @return string|array
     */
    public function getContent()
    {
        return (string) $this->body;
    }
}
