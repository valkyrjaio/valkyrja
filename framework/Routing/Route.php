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

/**
 * Class Route
 *
 * @package Valkyrja\Routing
 *
 * @author Melech Mizrachi
 */
class Route
{
    /**
     * The path for this route.
     *
     * @var string
     */
    public $path;

    /**
     * Name of this route.
     *
     * @var string
     */
    public $name;

    /**
     * The controller to use for this route.
     *
     * @var string
     */
    public $controller;

    /**
     * The action to use for this route.
     *
     * @var string
     */
    public $action;

    /**
     * The handler for this route.
     *
     * @var \Closure
     */
    public $handler;

    /**
     * Any injectable classes from the service container for the action/handler.
     *
     * @var array
     */
    public $injectables = [];

    /**
     * Whether the route is dynamic.
     *
     * @var bool
     */
    public $dynamic = false;

    /**
     * Route constructor.
     *
     * @param string   $path        The route path
     * @param string   $name        [optional] The route name
     * @param string   $controller  [optional] The route controller
     * @param string   $action      [optional] The route action
     * @param \Closure $handler     [optional] The route handler
     * @param array    $injectables [optional] The route injectable objects
     */
    public function __construct(
        string $path,
        string $name = null,
        string $controller = null,
        string $action = null,
        ?Closure $handler = null,
        array $injectables = []
    ) {
        $this->path = $path;
        $this->name = $name;
        $this->controller = $controller;
        $this->action = $action;
        $this->handler = $handler;
        $this->injectables = $injectables;
    }

    /**
     * Get the route's path.
     *
     * @return string
     */
    public function getPath() : string
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
    public function setPath(string $path) : Route
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the route's name.
     *
     * @return string
     */
    public function getName() : string
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
    public function setName(string $name) : Route
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the route's controller.
     *
     * @return string
     */
    public function getController(): string
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
    public function setController(string $controller) : Route
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * Get the route's action.
     *
     * @return string
     */
    public function getAction() : string
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
    public function setAction(string $action) : Route
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get the route's handler.
     *
     * @return \Closure
     */
    public function getHandler() : Closure
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
    public function setHandler(Closure $handler) : Route
    {
        $this->handler = $handler;

        return $this;
    }

    /**
     * Get the route's injectables.
     *
     * @return array
     */
    public function getInjectables() : array
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
    public function setInjectables(array $injectables) : Route
    {
        $this->injectables = $injectables;

        return $this;
    }

    /**
     * Check whether the route is dynamic.
     *
     * @return boolean
     */
    public function isDynamic(): bool
    {
        return $this->dynamic;
    }

    /**
     * Set the route as dynamic.
     *
     * @return \Valkyrja\Routing\Route
     */
    public function setDynamic() : Route
    {
        $this->dynamic = true;

        return $this;
    }
}
