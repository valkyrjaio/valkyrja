<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Collectors;

use Closure;
use InvalidArgumentException;
use Valkyrja\Http\Constants\RequestMethod;
use Valkyrja\Routing\Collector as Contract;
use Valkyrja\Routing\Models\Route as RouteModel;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Router;

/**
 * Class Collector.
 *
 * @author Melech Mizrachi
 */
class Collector implements Contract
{
    use CollectorHelpers;

    /**
     * Collector constructor.
     *
     * @param Router $router The router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

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
     * Helper method to set a GET route.
     * <code>
     *      get('/', 'Controller->property')
     *      get('/', 'Controller::property')
     *      get('/', 'Controller->method()')
     *      get('/', 'Controller::method()')
     *      get('/', 'function()')
     *      get('/', Closure)
     * </code>
     *
     * @param string         $path            The path
     * @param string|Closure $handler         The handler
     * @param string|null    $name            [optional] The name of the route
     * @param bool           $setDependencies [optional] Whether to dynamically set dependencies
     *
     * @throws InvalidArgumentException
     *
     * @return Route
     */
    public function get(string $path, $handler, string $name = null, bool $setDependencies = true): Route
    {
        $route = $this->getRouteForHelper($path, $handler, $name, $setDependencies);

        $route->setMethods([RequestMethod::GET]);

        return $route;
    }

    /**
     * Helper method to set a POST route.
     * <code>
     *      post('/', 'Controller->property')
     *      post('/', 'Controller::property')
     *      post('/', 'Controller->method()')
     *      post('/', 'Controller::method()')
     *      post('/', 'function()')
     *      post('/', Closure)
     * </code>
     *
     * @param string         $path            The path
     * @param string|Closure $handler         The handler
     * @param string|null    $name            [optional] The name of the route
     * @param bool           $setDependencies [optional] Whether to dynamically set dependencies
     *
     * @throws InvalidArgumentException
     *
     * @return Route
     */
    public function post(string $path, $handler, string $name = null, bool $setDependencies = true): Route
    {
        $route = $this->getRouteForHelper($path, $handler, $name, $setDependencies);

        $route->setMethods([RequestMethod::POST]);

        return $route;
    }

    /**
     * Helper method to set a PUT route.
     * <code>
     *      put('/', 'Controller->property')
     *      put('/', 'Controller::property')
     *      put('/', 'Controller->method()')
     *      put('/', 'Controller::method()')
     *      put('/', 'function()')
     *      put('/', Closure)
     * </code>
     *
     * @param string         $path            The path
     * @param string|Closure $handler         The handler
     * @param string|null    $name            [optional] The name of the route
     * @param bool           $setDependencies [optional] Whether to dynamically set dependencies
     *
     * @throws InvalidArgumentException
     *
     * @return Route
     */
    public function put(string $path, $handler, string $name = null, bool $setDependencies = true): Route
    {
        $route = $this->getRouteForHelper($path, $handler, $name, $setDependencies);

        $route->setMethods([RequestMethod::PUT]);

        return $route;
    }

    /**
     * Helper method to set a PATCH route.
     * <code>
     *      patch('/', 'Controller->property')
     *      patch('/', 'Controller::property')
     *      patch('/', 'Controller->method()')
     *      patch('/', 'Controller::method()')
     *      patch('/', 'function()')
     *      patch('/', Closure)
     * </code>
     *
     * @param string         $path            The path
     * @param string|Closure $handler         The handler
     * @param string|null    $name            [optional] The name of the route
     * @param bool           $setDependencies [optional] Whether to dynamically set dependencies
     *
     * @throws InvalidArgumentException
     *
     * @return Route
     */
    public function patch(string $path, $handler, string $name = null, bool $setDependencies = true): Route
    {
        $route = $this->getRouteForHelper($path, $handler, $name, $setDependencies);

        $route->setMethods([RequestMethod::PATCH]);

        return $route;
    }

    /**
     * Helper method to set a DELETE route.
     * <code>
     *      delete('/', 'Controller->property')
     *      delete('/', 'Controller::property')
     *      delete('/', 'Controller->method()')
     *      delete('/', 'Controller::method()')
     *      delete('/', 'function()')
     *      delete('/', Closure)
     * </code>
     *
     * @param string         $path            The path
     * @param string|Closure $handler         The handler
     * @param string|null    $name            [optional] The name of the route
     * @param bool           $setDependencies [optional] Whether to dynamically set dependencies
     *
     * @throws InvalidArgumentException
     *
     * @return Route
     */
    public function delete(string $path, $handler, string $name = null, bool $setDependencies = true): Route
    {
        $route = $this->getRouteForHelper($path, $handler, $name, $setDependencies);

        $route->setMethods([RequestMethod::DELETE]);

        return $route;
    }

    /**
     * Helper method to set a HEAD route.
     * <code>
     *      head('/', 'Controller->property')
     *      head('/', 'Controller::property')
     *      head('/', 'Controller->method()')
     *      head('/', 'Controller::method()')
     *      head('/', 'function()')
     *      head('/', Closure)
     * </code>
     *
     * @param string         $path            The path
     * @param string|Closure $handler         The handler
     * @param string|null    $name            [optional] The name of the route
     * @param bool           $setDependencies [optional] Whether to dynamically set dependencies
     *
     * @throws InvalidArgumentException
     *
     * @return Route
     */
    public function head(string $path, $handler, string $name = null, bool $setDependencies = true): Route
    {
        $route = $this->getRouteForHelper($path, $handler, $name, $setDependencies);

        $route->setMethods([RequestMethod::HEAD]);

        return $route;
    }

    /**
     * Helper method to set any request method route.
     * <code>
     *      any('/', 'Controller->property')
     *      any('/', 'Controller::property')
     *      any('/', 'Controller->method()')
     *      any('/', 'Controller::method()')
     *      any('/', 'function()')
     *      any('/', Closure)
     * </code>
     *
     * @param string         $path            The path
     * @param string|Closure $handler         The handler
     * @param string|null    $name            [optional] The name of the route
     * @param bool           $setDependencies [optional] Whether to dynamically set dependencies
     *
     * @throws InvalidArgumentException
     *
     * @return Route
     */
    public function any(string $path, $handler, string $name = null, bool $setDependencies = true): Route
    {
        $route = $this->getRouteForHelper($path, $handler, $name, $setDependencies);

        $route->setMethods(RequestMethod::ANY);

        return $route;
    }

    /**
     * Helper method to set any request method route.
     * <code>
     *      redirect('/', '/to', ['GET'], 301, true)
     * </code>
     *
     * @param string      $path    The path
     * @param string      $to      The path to redirect to
     * @param array|null  $methods [optional] The request methods
     * @param string|null $name    [optional] The name of the route
     *
     * @throws InvalidArgumentException
     *
     * @return Route
     */
    public function redirect(string $path, string $to, array $methods = null, string $name = null): Route
    {
        return (new RouteModel())
            ->setPath($path)
            ->setTo($to)
            ->setName($name)
            ->setMethods($methods ?? [RequestMethod::GET, RequestMethod::HEAD]);
    }
}
