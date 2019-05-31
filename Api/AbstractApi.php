<?php

namespace Chaplean\Bundle\ApiClientBundle\Api;

use GuzzleHttp\ClientInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractApi.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\API
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
abstract class AbstractApi
{
    /**
     * @var null|Route
     */
    protected $globalRoute = null;

    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var GlobalParameters
     */
    protected $globalParameters;

    /**
     * AbstractApi constructor.
     *
     * @param ClientInterface          $client
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(ClientInterface $client, EventDispatcherInterface $eventDispatcher)
    {
        $this->client = $client;
        $this->eventDispatcher = $eventDispatcher;

        $this->globalParameters = new GlobalParameters();

        $this->buildApi();
    }

    /**
     * Defines the API you want, using the methods of AbstractApi (get, post, …)
     *
     * @return void
     */
    abstract public function buildApi();

    /**
     * @param string $method
     * @param array  $args
     *
     * @return Route
     * @throws \Exception
     */
    public function __call($method, array $args)
    {
        $loweredMethod = strtolower($method);

        $request = null;
        $function = null;

        foreach (['delete', 'get', 'post', 'put', 'patch'] as $availableRequest) {
            if (strpos($loweredMethod, $availableRequest) === 0) {
                $request = $availableRequest;
                $function = substr($loweredMethod, strlen($availableRequest));
            }
        }

        if (empty($request) || empty($function)) {
            throw new \Exception(sprintf('%s is invalid, it should start with a HTTP verb', $method));
        }

        if (!isset($this->routes[$request]) || !isset($this->routes[$request][$function])) {
            throw new \InvalidArgumentException(sprintf('Method "%s" not available in this API actions list', $method));
        }

        return clone $this->routes[$request][$function];
    }

    /**
     * Configuration to apply to all created routes
     *
     * @return GlobalParameters
     * @throws \InvalidArgumentException
     */
    public function globalParameters()
    {
        if (!empty($this->routes)) {
            throw new \LogicException('globalParameters() must be called before any route definition');
        }

        return $this->globalParameters;
    }

    /**
     * @param string $method
     * @param string $name
     * @param Route  $route
     *
     * @return Route
     */
    protected function addRoute($method, $name, Route $route)
    {
        $name = strtolower($name);

        if (isset($this->routes[$method])) {
            $this->routes[$method][$name] = $route;
        } else {
            $this->routes[$method] = [$name => $route];
        }

        return $route;
    }

    /**
     * @param string $name
     * @param string $url
     *
     * @return Route
     */
    protected function get($name, $url)
    {
        return $this->addRoute('get', $name, new Route(Request::METHOD_GET, $url, $this->client, $this->eventDispatcher, $this->globalParameters, $this->getName()));
    }

    /**
     * @param string $name
     * @param string $url
     *
     * @return Route
     */
    protected function post($name, $url)
    {
        return $this->addRoute('post', $name, new Route(Request::METHOD_POST, $url, $this->client, $this->eventDispatcher, $this->globalParameters, $this->getName()));
    }

    /**
     * @param string $name
     * @param string $url
     *
     * @return Route
     */
    protected function put($name, $url)
    {
        return $this->addRoute('put', $name, new Route(Request::METHOD_PUT, $url, $this->client, $this->eventDispatcher, $this->globalParameters, $this->getName()));
    }

    /**
     * @param string $name
     * @param string $url
     *
     * @return Route
     */
    protected function patch($name, $url)
    {
        return $this->addRoute('patch', $name, new Route(Request::METHOD_PATCH, $url, $this->client, $this->eventDispatcher, $this->globalParameters, $this->getName()));
    }

    /**
     * @param string $name
     * @param string $url
     *
     * @return Route
     */
    protected function delete($name, $url)
    {
        return $this->addRoute('delete', $name, new Route(Request::METHOD_DELETE, $url, $this->client, $this->eventDispatcher, $this->globalParameters, $this->getName()));
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        $class = new \ReflectionClass($this);
        return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $class->getShortName()));
    }
}
