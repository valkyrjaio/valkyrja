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
     * The request method for this route.
     *
     * @var string
     */
    protected $requestMethod;

    /**
     * The handler for this route.
     *
     * @var \Closure
     */
    protected $handler;

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
     * Any matches for dynamic routes.
     *
     * @var array
     */
    protected $matches;

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
     * Get the request method.
     *
     * @return string
     */
    public function getRequestMethod(): string
    {
        if (null === $this->requestMethod) {
            $this->requestMethod = RequestMethod::GET;
        }

        return $this->requestMethod;
    }

    /**
     * Set the method.
     *
     * @param string $requestMethod The request method
     *
     * @return $this
     */
    public function setRequestMethod(string $requestMethod = null): self
    {
        $this->requestMethod = $requestMethod;

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
            ->setRequestMethod($properties['requestMethod'] ?? null)
            ->setRegex($properties['regex'] ?? null)
            ->setParams($properties['params'] ?? null)
            ->setMatches($properties['matches'] ?? null)
            ->setDynamic($properties['dynamic'] ?? false)
            ->setSecure($properties['secure'] ?? false)
            ->setClass($properties['class'] ?? null)
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
