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

namespace Valkyrja\Routing;

use Closure;

/**
 * Interface RouteMethods.
 *
 * @author Melech Mizrachi
 */
interface RouteMethods
{
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
     * @return Route
     */
    public function get(string $path, $handler, string $name = null, bool $setDependencies = false): Route;

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
     * @return Route
     */
    public function post(string $path, $handler, string $name = null, bool $setDependencies = false): Route;

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
     * @return Route
     */
    public function put(string $path, $handler, string $name = null, bool $setDependencies = false): Route;

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
     * @return Route
     */
    public function patch(string $path, $handler, string $name = null, bool $setDependencies = false): Route;

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
     * @return Route
     */
    public function delete(string $path, $handler, string $name = null, bool $setDependencies = false): Route;

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
     * @return Route
     */
    public function head(string $path, $handler, string $name = null, bool $setDependencies = false): Route;

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
     * @return Route
     */
    public function any(string $path, $handler, string $name = null, bool $setDependencies = false): Route;

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
     * @return Route
     */
    public function redirect(string $path, string $to, array $methods = null, string $name = null): Route;
}
