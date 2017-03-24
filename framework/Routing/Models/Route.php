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
     * Whether the route is dynamic.
     *
     * @var bool
     */
    protected $dynamic = false;

    /**
     * Get the route's method.
     *
     * @return string
     */
    public function getMethod():? string
    {
        return $this->method;
    }

    /**
     * Set the route's method.
     *
     * @param string $method The route method
     *
     * @return \Valkyrja\Routing\Route
     */
    public function setMethod(string $method): Route
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
     * @return \Valkyrja\Routing\Route
     */
    public function setPath(string $path): Route
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
     * @return \Valkyrja\Routing\Route
     */
    public function setName(string $name): Route
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
     * @return \Valkyrja\Routing\Route
     */
    public function setController(string $controller): Route
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
     * @return \Valkyrja\Routing\Route
     */
    public function setAction(string $action): Route
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
     * @return \Valkyrja\Routing\Route
     */
    public function setHandler(Closure $handler): Route
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
     * @return \Valkyrja\Routing\Route
     */
    public function setInjectables(array $injectables): Route
    {
        $this->injectables = $injectables;

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
     * @return \Valkyrja\Routing\Route
     */
    public function setDynamic(bool $dynamic): Route
    {
        $this->dynamic = $dynamic;

        return $this;
    }
}
