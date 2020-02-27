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
use InvalidArgumentException;
use Valkyrja\Http\Enums\RequestMethod;
use Valkyrja\Routing\Models\Route as RouteModel;
use Valkyrja\Routing\Route;

use function is_string;

/**
 * Trait MethodHelpers.
 *
 * @author Melech Mizrachi
 */
trait MethodHelpers
{
    /**
     * The static handler split.
     *
     * @var string
     */
    protected static string $staticHandlerSplit = '::';

    /**
     * The handler split.
     *
     * @var string
     */
    protected static string $handlerSplit = '->';

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
     * @param string         $path    The path
     * @param string|Closure $handler The handler
     * @param string|null    $name    [optional] The name of the route
     *
     * @throws InvalidArgumentException
     *
     * @return Route
     */
    public function get(string $path, $handler, string $name = null): Route
    {
        $route = $this->getRouteForHelper($path, $handler, $name);

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
     * @param string         $path    The path
     * @param string|Closure $handler The handler
     * @param string|null    $name    [optional] The name of the route
     *
     * @throws InvalidArgumentException
     *
     * @return Route
     */
    public function post(string $path, $handler, string $name = null): Route
    {
        $route = $this->getRouteForHelper($path, $handler, $name);

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
     * @param string         $path    The path
     * @param string|Closure $handler The handler
     * @param string|null    $name    [optional] The name of the route
     *
     * @throws InvalidArgumentException
     *
     * @return Route
     */
    public function put(string $path, $handler, string $name = null): Route
    {
        $route = $this->getRouteForHelper($path, $handler, $name);

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
     * @param string         $path    The path
     * @param string|Closure $handler The handler
     * @param string|null    $name    [optional] The name of the route
     *
     * @throws InvalidArgumentException
     *
     * @return Route
     */
    public function patch(string $path, $handler, string $name = null): Route
    {
        $route = $this->getRouteForHelper($path, $handler, $name);

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
     * @param string         $path    The path
     * @param string|Closure $handler The handler
     * @param string|null    $name    [optional] The name of the route
     *
     * @throws InvalidArgumentException
     *
     * @return Route
     */
    public function delete(string $path, $handler, string $name = null): Route
    {
        $route = $this->getRouteForHelper($path, $handler, $name);

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
     * @param string         $path    The path
     * @param string|Closure $handler The handler
     * @param string|null    $name    [optional] The name of the route
     *
     * @throws InvalidArgumentException
     *
     * @return Route
     */
    public function head(string $path, $handler, string $name = null): Route
    {
        $route = $this->getRouteForHelper($path, $handler, $name);

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
     * @param string         $path    The path
     * @param string|Closure $handler The handler
     * @param string|null    $name    [optional] The name of the route
     *
     * @throws InvalidArgumentException
     *
     * @return Route
     */
    public function any(string $path, $handler, string $name = null): Route
    {
        $route = $this->getRouteForHelper($path, $handler, $name);

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
            ->setMethods($methods);
    }

    /**
     * Set a single route.
     *
     * @param Route $route
     *
     * @return void
     */
    abstract public function addRoute(Route $route): void;

    /**
     * Get a route for a helper method.
     *
     * @param string         $path    The path
     * @param string|Closure $handler The handler
     * @param string|null    $name    [optional] The name of the route
     *
     * @throws InvalidArgumentException
     *
     * @return Route
     */
    protected function getRouteForHelper(string $path, $handler, string $name = null): Route
    {
        $route = new RouteModel();

        $route->setPath($path);
        $route->setName($name);

        $this->setRouteHandler($route, $handler);
        $this->addRoute($route);

        return $route;
    }

    /**
     * Set the route handler.
     *
     * @param Route          $route   The route
     * @param string|Closure $handler The handler
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    protected function setRouteHandler(Route $route, $handler): void
    {
        $this->verifyHandler($handler);

        if ($handler instanceof Closure) {
            $route->setClosure($handler);

            return;
        }

        $this->setRouteHandlerFromString($route, $handler);
    }

    /**
     * Verify a handler.
     *
     * @param string|Closure $handler The handler
     *
     * @return void
     */
    protected function verifyHandler($handler): void
    {
        if (! is_string($handler) && ! ($handler instanceof Closure)) {
            throw new InvalidArgumentException('Invalid handler provided.');
        }
    }

    /**
     * Set the route handler from a string.
     *
     * @param Route  $route   The route
     * @param string $handler The handler
     *
     * @return void
     */
    protected function setRouteHandlerFromString(Route $route, string $handler): void
    {
        if (strpos($handler, self::$handlerSplit) !== false) {
            $this->setRouteInstanceHandler($route, $handler);

            return;
        }

        if (strpos($handler, self::$staticHandlerSplit) !== false) {
            $this->setRouteStaticHandler($route, $handler);

            return;
        }

        $route->setFunction($handler);
    }

    /**
     * Set the instance route handler.
     *
     * @param Route  $route   The route
     * @param string $handler The handler
     *
     * @return void
     */
    protected function setRouteInstanceHandler(Route $route, string $handler): void
    {
        $this->setRouteHandlerSplit($route, $handler, self::$handlerSplit);
    }

    /**
     * Set the static route handler.
     *
     * @param Route  $route   The route
     * @param string $handler The handler
     *
     * @return void
     */
    protected function setRouteStaticHandler(Route $route, string $handler): void
    {
        $this->setRouteHandlerSplit($route, $handler, self::$staticHandlerSplit);
    }

    /**
     * Set the static route handler.
     *
     * @param Route  $route     The route
     * @param string $handler   The handler
     * @param string $delimiter The delimiter
     *
     * @return void
     */
    protected function setRouteHandlerSplit(Route $route, string $handler, string $delimiter): void
    {
        [$class, $member] = explode($delimiter, $handler);

        $route->setClass($class);
        $this->setRouteMember($route, $member);
    }

    /**
     * Set the route handler member.
     *
     * @param Route  $route  The route
     * @param string $member The member
     *
     * @return void
     */
    protected function setRouteMember(Route $route, string $member): void
    {
        if (strpos($member, '(') !== false) {
            $member = str_replace('()', '', $member);

            $route->setMethod($member);

            return;
        }

        $route->setProperty($member);
    }
}
