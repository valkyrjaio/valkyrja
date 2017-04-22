<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Models;

use Closure;

use Valkyrja\Http\RequestMethod;
use Valkyrja\Model\Model;

/**
 * Class Route
 *
 * @package Valkyrja\Routing\Models
 *
 * @author  Melech Mizrachi
 */
class Route extends Model
{
    /**
     * The method for this route.
     *
     * @var string
     */
    protected $method;

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
     * The controller to use for this route.
     *
     * @var string
     */
    protected $controller;

    /**
     * The action to use for this route.
     *
     * @var string
     */
    protected $action;

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
    protected $injectables = [];

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
     * Get the method.
     *
     * @return string
     */
    public function getMethod(): string
    {
        if (null === $this->method) {
            $this->method = RequestMethod::GET;
        }

        return $this->method;
    }

    /**
     * Set the method.
     *
     * @param string $method The method
     *
     * @return \Valkyrja\Routing\Models\Route
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

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
     * @return \Valkyrja\Routing\Models\Route
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
     * @return \Valkyrja\Routing\Models\Route
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the route's controller.
     *
     * @return string
     */
    public function getController():? string
    {
        return $this->controller;
    }

    /**
     * Set the route's controller.
     *
     * @param string $controller The controller to use
     *
     * @return \Valkyrja\Routing\Models\Route
     */
    public function setController(string $controller): self
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * Get the route's action.
     *
     * @return string
     */
    public function getAction():? string
    {
        return $this->action;
    }

    /**
     * Set the route's action.
     *
     * @param string $action The action to use in the controller
     *
     * @return \Valkyrja\Routing\Models\Route
     */
    public function setAction(string $action): self
    {
        $this->action = $action;

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
     * @return \Valkyrja\Routing\Models\Route
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
    public function getInjectables(): array
    {
        return $this->injectables;
    }

    /**
     * Set the route's injectables.
     *
     * @param array $injectables List of dependency injectable objects for the action/handler
     *
     * @return \Valkyrja\Routing\Models\Route
     */
    public function setInjectables(array $injectables): self
    {
        $this->injectables = $injectables;

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
     * @return \Valkyrja\Routing\Models\Route
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
     * @return \Valkyrja\Routing\Models\Route
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
     * @return \Valkyrja\Routing\Models\Route
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
     * @return \Valkyrja\Routing\Models\Route
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
     * @return \Valkyrja\Routing\Models\Route
     *
     * @throws \InvalidArgumentException
     */
    public static function getRoute(array $properties): self
    {
        $route = new Route();

        $route
            ->setPath($properties['path'])
            ->setName($properties['name'])
            ->setMethod($properties['method'])
            ->setAction($properties['action'])
            ->setController($properties['controller'])
            ->setInjectables($properties['injectables'])
            ->setParams($properties['params'])
            ->setMatches($properties['matches'])
            ->setDynamic($properties['dynamic'])
            ->setSecure($properties['secure'])
        ;

        return $route;
    }

    /**
     * Set the state of the route.
     *
     * @param array $properties The properties to set
     *
     * @return \Valkyrja\Routing\Models\Route
     *
     * @throws \InvalidArgumentException
     */
    public static function __set_state(array $properties): self
    {
        return static::getRoute($properties);
    }
}
