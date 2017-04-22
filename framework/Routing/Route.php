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

use Closure;

use Valkyrja\Annotations\Annotation;
use Valkyrja\Http\RequestMethod;

/**
 * Class Route
 *
 * @package Valkyrja\Routing
 *
 * @author  Melech Mizrachi
 */
class Route extends Annotation
{
    /**
     * The path for this route.
     *
     * @var string
     */
    protected $path;

    /**
     * Name of this route.
     *
     * @var string
     */
    protected $name;
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
     * Any injectable classes from the service container for the action/handler.
     *
     * @var array
     */
    protected $dependencies = [];

    /**
     * Any params for dynamic routes.
     *
     * @var array
     */
    protected $params = [];

    /**
     * Any matches for dynamic routes.
     *
     * @var array
     */
    protected $matches = [];

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
     * Get the route's name.
     *
     * @return string
     */
    public function getName():? string
    {
        return $this->name;
    }

    /**
     * Set the route's name.
     *
     * @param string $name The route's name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

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
    public function setRequestMethod(string $requestMethod): self
    {
        $this->requestMethod = $requestMethod;

        return $this;
    }

    /**
     * Get the route's handler.
     *
     * @return \Closure
     */
    public function getHandler():? Closure
    {
        return $this->handler;
    }

    /**
     * Set the route's handler.
     *
     * @param \Closure $handler The closure to handle to route
     *
     * @return $this
     */
    public function setHandler(Closure $handler): self
    {
        $this->handler = $handler;

        return $this;
    }

    /**
     * Get the route's injectables.
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    /**
     * Set the route's dependencies.
     *
     * @param array $dependencies List of dependency injectable objects for the action/handler
     *
     * @return $this
     */
    public function setDependencies(array $dependencies): self
    {
        $this->dependencies = $dependencies;

        return $this;
    }

    /**
     * Get the params.
     *
     * @return array
     */
    public function getParams(): array
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
    public function setParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get the matches.
     *
     * @return array
     */
    public function getMatches(): array
    {
        return $this->matches;
    }

    /**
     * Set the matches.
     *
     * @param array $matches The matches
     *
     * @return $this
     */
    public function setMatches(array $matches): self
    {
        $this->matches = $matches;

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
     *
     * @throws \InvalidArgumentException
     */
    public static function getRoute(array $properties): self
    {
        $route = new Route();

        $route
            ->setPath($properties['path'])
            ->setName($properties['name'])
            ->setRequestMethod($properties['requestMethod'])
            ->setDependencies($properties['dependencies'])
            ->setParams($properties['params'])
            ->setDynamic($properties['dynamic'])
            ->setSecure($properties['secure'])
            ->setMatches($properties['matches'])
            ->setMethod($properties['method'])
            ->setFunction($properties['function'])
            ->setClass($properties['class'])
        ;

        return $route;
    }

    /**
     * Set the state of the route.
     *
     * @param array $properties The properties to set
     *
     * @return \Valkyrja\Routing\Route
     *
     * @throws \InvalidArgumentException
     */
    public static function __set_state(array $properties): self
    {
        return static::getRoute($properties);
    }
}
