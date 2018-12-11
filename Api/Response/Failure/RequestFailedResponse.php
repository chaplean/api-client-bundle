<?php

namespace Chaplean\Bundle\ApiClientBundle\Api\Response\Failure;

use GuzzleHttp\Exception\TransferException;

/**
 * Class RequestFailedResponse.
 *
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class RequestFailedResponse extends AbstractFailureResponse
{
    protected $message;

    /**
     * RequestFailedResponse constructor.
     *
     * @param TransferException $e
     * @param string            $method
     * @param string            $url
     * @param array             $data
     */
    public function __construct(TransferException $e, $method = null, $url = null, array $data = null)
    {
        parent::__construct($method, $url, $data);

        $this->message = $e->getMessage();
    }

    /**
     * Returns the content of the response to the executed request
     * or the error message if the request failed to execute
     *
     * @return string|array
     */
    public function getContent()
    {
        return $this->message;
    }
}
