<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Helpers;

use Closure;
use Valkyrja\Routing\Models\Route as RouteModel;
use Valkyrja\Routing\Route;

use function is_array;

/**
 * Trait RouteGroup.
 *
 * @author Melech Mizrachi
 */
trait RouteGroup
{
    /**
     * The route context.
     *
     * @var Route|null
     */
    protected ?Route $route = null;

    /**
     * Get a router with a path context to group routes with.
     *
     * @param string $path The path
     *
     * @return static
     */
    public function withPath(string $path): self
    {
        return $this->withGroupableSelf('setPath', $path);
    }

    /**
     * Get a router with a controller context to group routes with.
     *
     * @param string $controller The controller
     *
     * @return static
     */
    public function withController(string $controller): self
    {
        return $this->withGroupableSelf('setClass', $controller);
    }

    /**
     * Get a router with a name context to group routes with.
     *
     * @param string $name The name
     *
     * @return static
     */
    public function withName(string $name): self
    {
        return $this->withGroupableSelf('setName', $name);
    }

    /**
     * Get a router with middleware context to group routes with.
     *
     * @param array $middleware The middleware
     *
     * @return static
     */
    public function withMiddleware(array $middleware): self
    {
        return $this->withGroupableSelf('setMiddleware', $middleware);
    }

    /**
     * Get a router with a secure context to group routes with.
     *
     * @param bool $secure [optional] Whether to be secure
     *
     * @return static
     */
    public function withSecure(bool $secure = true): self
    {
        return $this->withGroupableSelf('setSecure', $secure);
    }

    /**
     * Group routes together.
     *
     * @param Closure $group The group
     *
     * @return static
     */
    public function group(Closure $group): self
    {
        $group($this);

        return $this;
    }

    /**
     * Ensure a route context is set so we can chain 'with' group methods.
     *
     * @return void
     */
    protected function ensureRoute(): void
    {
        if (null === $this->route) {
            $this->route = new RouteModel();
        }
    }

    /**
     * With a new group instance to set the route method value.
     *
     * @param string $method
     * @param mixed  $value
     *
     * @return static
     */
    protected function withGroupableSelf(string $method, $value): self
    {
        $self = clone $this;

        $self->ensureRoute();
        $self->route->{$method}($value);

        return $self;
    }

    /**
     * Set a controller context in a route.
     *
     * @param Route $route The route
     *
     * @return void
     */
    protected function setPathInRoute(Route $route): void
    {
        $this->setPropertyInRoute($route, 'Path', true);
    }

    /**
     * Set a controller context in a route.
     *
     * @param Route $route The route
     *
     * @return void
     */
    protected function setControllerInRoute(Route $route): void
    {
        $this->setPropertyInRoute($route, 'Class');
    }

    /**
     * Set a controller context in a route.
     *
     * @param Route $route The route
     *
     * @return void
     */
    protected function setNameInRoute(Route $route): void
    {
        $this->setPropertyInRoute($route, 'Name', true);
    }

    /**
     * Set a controller context in a route.
     *
     * @param Route $route The route
     *
     * @return void
     */
    protected function setMiddlewareInRoute(Route $route): void
    {
        $this->setPropertyInRoute($route, 'Middleware', true);
    }

    /**
     * Set a secure context in a route.
     *
     * @param Route $route The route
     *
     * @return void
     */
    protected function setSecureInRoute(Route $route): void
    {
        $this->setPropertyInRoute($route, 'Secure');
    }

    /**
     * Set a property in a route if it exists in the context route.
     *
     * @param Route  $route  The route
     * @param string $method The method to set
     * @param bool   $merge  [optional] Whether to merge the values
     *
     * @return void
     */
    protected function setPropertyInRoute(Route $route, string $method, bool $merge = false): void
    {
        if (null === $this->route) {
            return;
        }

        $getMethod = $method === 'Secure' ? "is$method" : "get$method";
        $setMethod = "set$method";

        if ($value = $this->route->{$getMethod}()) {
            if ($merge) {
                $value = $this->mergePropertiesForRoute($value, $route->{$getMethod}(), $method === 'Name' ? '.' : '');
            }

            $route->{$setMethod}($value);
        }
    }

    /**
     * @param string|array $value      The context route value
     * @param string|array $routeValue The route value
     * @param string       $glue       [optional] The glue
     *
     * @return string|array
     */
    protected function mergePropertiesForRoute($value, $routeValue, string $glue = '')
    {
        if (is_array($value)) {
            return array_merge($value, $routeValue);
        }

        return $value . $glue . $routeValue;
    }
}
