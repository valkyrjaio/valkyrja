<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing;

use Valkyrja\Contracts\Annotations\Annotation;
use Valkyrja\Dispatcher\Dispatch;
use Valkyrja\Http\RequestMethod;

/**
 * Class Route
 *
 * @package Valkyrja\Routing
 *
 * @author  Melech Mizrachi
 */
class Route extends Dispatch implements Annotation
{
    /**
     * The path for this route.
     *
     * @var string
     */
    protected $path;

    /**
     * The request methods for this route.
     *
     * @var array
     */
    protected $requestMethods;

    /**
     * The regex for dynamic routes.
     *
     * @var string
     */
    protected $regex;

    /**
     * Any params for dynamic routes.
     *
     * @var array
     */
    protected $params;

    /**
     * Any segments for optional parts of path.
     *
     * @var array
     */
    protected $segments;

    /**
     * Whether the route is dynamic.
     *
     * @var bool
     */
    protected $dynamic = false;

    /**
     * Whether the route is secure.
     *
     * @var bool
     */
    protected $secure = false;

    /**
     * Get the route's path.
     *
     * @return string
     */
    public function getPath():? string
    {
        return $this->path;
    }

    /**
     * Set the route's path.
     *
     * @param string $path The route path
     *
     * @return $this
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the request methods.
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function getRequestMethods(): array
    {
        if (null === $this->requestMethods) {
            $this->requestMethods = [
                RequestMethod::GET,
                RequestMethod::HEAD,
            ];
        }

        return $this->requestMethods;
    }

    /**
     * Set the request methods.
     *
     * @param array $requestMethods The request methods
     *
     * @return $this
     */
    public function setRequestMethods(array $requestMethods): self
    {
        if (array_diff($requestMethods, RequestMethod::validValues())) {
            throw new \InvalidArgumentException('Invalid request methods set');
        }

        $this->requestMethods = $requestMethods;

        return $this;
    }

    /**
     * Get the regex.
     *
     * @return string
     */
    public function getRegex():? string
    {
        return $this->regex;
    }

    /**
     * Set the regex.
     *
     * @param string $regex The regex
     *
     * @return $this
     */
    public function setRegex(string $regex = null): self
    {
        $this->regex = $regex;

        return $this;
    }

    /**
     * Get the params.
     *
     * @return array
     */
    public function getParams():? array
    {
        return $this->params;
    }

    /**
     * Set the params.
     *
     * @param array $params The params
     *
     * @return $this
     */
    public function setParams(array $params = null): self
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get the segments.
     *
     * @return array
     */
    public function getSegments():? array
    {
        return $this->segments;
    }

    /**
     * Set the segments.
     *
     * @param array $segments The segments
     *
     * @return $this
     */
    public function setSegments(array $segments = null): self
    {
        $this->segments = $segments;

        return $this;
    }

    /**
     * Check whether the route is dynamic.
     *
     * @return boolean
     */
    public function getDynamic(): bool
    {
        return $this->dynamic;
    }

    /**
     * Set the route as dynamic.
     *
     * @param bool $dynamic Whether the route it dynamic
     *
     * @return $this
     */
    public function setDynamic(bool $dynamic): self
    {
        $this->dynamic = $dynamic;

        return $this;
    }

    /**
     * Get whether the route is secure.
     *
     * @return bool
     */
    public function getSecure(): bool
    {
        return $this->secure;
    }

    /**
     * Set whether the route is secure.
     *
     * @param bool $secure Whether the route is secure
     *
     * @return $this
     */
    public function setSecure(bool $secure): self
    {
        $this->secure = $secure;

        return $this;
    }

    /**
     * Get a route from properties.
     *
     * @param array $properties The properties to set
     *
     * @return $this
     */
    public static function getRoute(array $properties): self
    {
        $route = new Route();

        $route
            ->setPath($properties['path'])
            ->setName($properties['name'] ?? null)
            ->setRequestMethods($properties['requestMethods'] ?? [])
            ->setRegex($properties['regex'] ?? null)
            ->setParams($properties['params'] ?? null)
            ->setSegments($properties['segments'] ?? null)
            ->setMatches($properties['matches'] ?? null)
            ->setDynamic($properties['dynamic'] ?? false)
            ->setSecure($properties['secure'] ?? false)
            ->setClass($properties['class'] ?? null)
            ->setProperty($properties['property'] ?? null)
            ->setMethod($properties['method'] ?? null)
            ->setFunction($properties['function'] ?? null)
            ->setClosure($properties['closure'] ?? null)
            ->setDependencies($properties['dependencies'] ?? null)
            ->setStatic($properties['static'] ?? null);

        return $route;
    }

    /**
     * Set the state of the route.
     *
     * @param array $properties The properties to set
     *
     * @return \Valkyrja\Routing\Route
     */
    public static function __set_state(array $properties)
    {
        return static::getRoute($properties);
    }
}
