<?php

namespace Chaplean\Bundle\ApiClientBundle\Api;

use Chaplean\Bundle\ApiClientBundle\Event\RequestExecutedEvent;
use Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\InvalidParameterResponse;
use Chaplean\Bundle\ApiClientBundle\Api\Response\Failure\RequestFailedResponse;
use Chaplean\Bundle\ApiClientBundle\Exception\ParameterConstraintValidationFailedException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Route.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\Api
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class Route
{
    protected $client;
    protected $eventDispatcher;

    protected $method;
    protected $url;
    protected $responseType;

    protected $urlPrefix;
    protected $urlSuffix;

    protected $urlParameters;
    protected $queryParameters;
    protected $headers;

    protected $requestType;
    protected $requestParameters;

    const REQUEST_FORM_URL_ENCODED = 'form_params';
    const REQUEST_JSON = 'json';
    const REQUEST_JSON_STRING = 'json_string';
    const REQUEST_XML = 'xml';
    const RESPONSE_BINARY = 'Binary';
    const RESPONSE_JSON = 'Json';
    const RESPONSE_PLAIN = 'Plain';
    const RESPONSE_XML = 'Xml';

    static protected $allowedMethods = [
        Request::METHOD_GET,
        Request::METHOD_POST,
        Request::METHOD_PUT,
        Request::METHOD_PATCH,
        Request::METHOD_DELETE,
    ];

    /**
     * Route constructor.
     *
     * @param string                   $method
     * @param string                   $url
     * @param ClientInterface          $client
     * @param EventDispatcherInterface $eventDispatcher
     * @param GlobalParameters         $globalParameters
     */
    public function __construct($method, $url, ClientInterface $client, EventDispatcherInterface $eventDispatcher, GlobalParameters $globalParameters)
    {
        if (!in_array($method, self::$allowedMethods, true)) {
            throw new \InvalidArgumentException();
        }

        $this->client = $client;
        $this->eventDispatcher = $eventDispatcher;

        $this->method = $method;
        $this->url = $url;
        $this->urlPrefix = $globalParameters->urlPrefix;
        $this->urlSuffix = $globalParameters->urlSuffix;

        $this->responseType = $globalParameters->responseType;
        $this->urlParameters = $globalParameters->urlParameters;
        $this->queryParameters = $globalParameters->queryParameters;
        $this->headers = $globalParameters->headers;

        $this->requestType = $globalParameters->requestType;
        $this->requestParameters = $globalParameters->requestParameters;

        $this->bindUrlParameters([]);
        $this->bindQueryParameters([]);
        $this->bindHeaders([]);
    }

    /**
     * @return self
     */
    public function allowExtraQueryParameters(): self
    {
        $this->queryParameters->allowExtraField();

        return $this;
    }

    /**
     * @return self
     */
    public function allowExtraRequestParameters(): self
    {
        $this->requestParameters->allowExtraField();

        return $this;
    }

    /**
     * Configure the route to expect a binary response (which is the default)
     *
     * @return self
     */
    public function expectsBinary()
    {
        $this->responseType = self::RESPONSE_BINARY;

        return $this;
    }

    /**
     * Configure the route to expect a plain text response (which is the default)
     *
     * @return self
     */
    public function expectsPlain()
    {
        $this->responseType = self::RESPONSE_PLAIN;

        return $this;
    }

    /**
     * Configure the route to expect a json response instead of plain text
     *
     * @return self
     */
    public function expectsJson()
    {
        $this->responseType = self::RESPONSE_JSON;

        return $this;
    }

    /**
     * Configure the route to expect a xml response instead of plain text
     *
     * @return self
     */
    public function expectsXml()
    {
        $this->responseType = self::RESPONSE_XML;

        return $this;
    }

    /**
     * Set url parameters for this route.
     *
     * @param array $parameters
     *
     * @return $this
     */
    public function urlParameters(array $parameters)
    {
        $this->urlParameters = Parameter::object($parameters);

        return $this;
    }

    /**
     * @param array $parameters
     *
     * @return $this
     */
    public function bindUrlParameters(array $parameters)
    {
        $this->urlParameters->setValue($parameters);

        return $this;
    }

    /**
     * Set query string parameters for this route.
     *
     * @param array $parameters
     *
     * @return $this
     */
    public function queryParameters(array $parameters)
    {
        $this->queryParameters = Parameter::object($parameters);

        return $this;
    }

    /**
     * @param array $parameters
     *
     * @return $this
     */
    public function bindQueryParameters(array $parameters)
    {
        $this->queryParameters->setValue($parameters);

        return $this;
    }

    /**
     * Set headers for this route.
     *
     * @param array $parameters
     *
     * @return $this
     */
    public function headers(array $parameters)
    {
        $this->headers = Parameter::object($parameters);

        return $this;
    }

    /**
     * @param array $parameters
     *
     * @return $this
     */
    public function bindHeaders(array $parameters)
    {
        $this->headers->setValue($parameters);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->urlPrefix . $this->fillInUrlPlaceholders() . $this->urlSuffix;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return ResponseInterface
     */
    public function exec()
    {
        $response = $this->sendRequest();
        $this->eventDispatcher->dispatch('chaplean_api_client.request_executed', new RequestExecutedEvent($response));

        return $response;
    }

    /**
     * Configure the route to send request data as application/x-www-form-urlencoded
     * (which is the default)
     *
     * @return self
     */
    public function sendFormUrlEncoded()
    {
        $this->requestType = self::REQUEST_FORM_URL_ENCODED;

        return $this;
    }

    /**
     * Configure the route to send request data as json
     *
     * @return self
     */
    public function sendJson()
    {
        $this->requestType = self::REQUEST_JSON;

        return $this;
    }

    /**
     * Configure the route to send request data as xml
     *
     * @return self
     */
    public function sendXml()
    {
        $this->requestType = self::REQUEST_XML;

        return $this;
    }

    /**
     * Configure the route to send request data as a url-encoded key-value pair where the key is JSONString and the
     * value is the request data in json format
     *
     * @return self
     */
    public function sendJSONString()
    {
        $this->requestType = Route::REQUEST_JSON_STRING;

        return $this;
    }

    /**
     * @return ResponseInterface
     */
    private function sendRequest()
    {
        try {
            $url = $this->getUrl();
            $options = $this->buildRequestOptions();
        } catch (ParameterConstraintValidationFailedException $e) {
            return new InvalidParameterResponse($this->getViolations(), $this->method, $this->urlPrefix . '/' . $this->url, []);
        }

        $responseClass = 'Chaplean\Bundle\ApiClientBundle\Api\Response\Success\\' . $this->responseType . 'Response';

        try {
            $response = $this->client->request($this->method, $url, $options);

            return new $responseClass($response, $this->method, $url, $options);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if ($response === null) {
                return new RequestFailedResponse($e, $this->method, $url, $options);
            }

            return new $responseClass($response, $this->method, $url, $options);
        } catch (TransferException $e) {
            return new RequestFailedResponse($e, $this->method, $url, $options);
        }
    }

    /**
     * @return string
     * @throws ParameterConstraintValidationFailedException
     */
    protected function fillInUrlPlaceholders()
    {
        $parts = explode('/', $this->url);

        $parts = array_filter($parts, function($element) {
            return $element !== '';
        });

        $parameters = $this->urlParameters->toArray();

        foreach ($parts as $id => $part) {
            $partsBis = explode('.', $part);
            foreach ($partsBis as $idBis => $partBis) {
                if (strpos($partBis, '{') === 0 && strrpos($partBis, '}') === strlen($partBis) - 1) {
                    $partBis = substr($partBis, 1, -1);
                    $partsBis[$idBis] = $parameters[$partBis];
                }
            }
            $parts[$id] = implode('.', $partsBis);
        }

        return implode('/', $parts);
    }

    /**
     * Set request parameters for this route.
     *
     * @param array $parameters
     *
     * @return $this
     */
    public function requestParameters(array $parameters)
    {
        $this->requestParameters = Parameter::Object($parameters);

        return $this;
    }

    /**
     * @param array $parameters
     *
     * @return $this
     */
    public function bindRequestParameters(array $parameters)
    {
        $this->requestParameters->setValue($parameters);

        return $this;
    }

    /**
     * @return array
     */
    protected function buildRequestParameters()
    {
        $requestType = $this->requestType;
        $requestData = $this->requestParameters->toArray();

        if ($this->requestType === self::REQUEST_JSON_STRING) {
            $requestType = self::REQUEST_FORM_URL_ENCODED;
            $requestData = ['JSONString' => json_encode($requestData)];
        }

        return [$requestType => $requestData];
    }

    /**
     * @return array
     * @throws ParameterConstraintValidationFailedException
     */
    protected function buildRequestOptions()
    {
        return array_merge([
            'headers' => $this->headers->toArray(),
            'query'   => $this->queryParameters->toArray(),
        ],
            $this->buildRequestParameters());
    }

    /**
     * @return ParameterConstraintViolationCollection
     */
    protected function getViolations()
    {
        $violations = new ParameterConstraintViolationCollection();
        $violations->addChild('url', $this->urlParameters->getViolations());
        $violations->addChild('header', $this->headers->getViolations());
        $violations->addChild('query', $this->queryParameters->getViolations());
        $violations->addChild('request', $this->requestParameters->getViolations());

        return $violations;
    }
}
