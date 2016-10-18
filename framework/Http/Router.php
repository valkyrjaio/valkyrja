<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http;

use Valkyrja\Application;
use Valkyrja\Contracts\Http\Router as RouterContract;

/**
 * Class Router
 *
 * @package Valkyrja\Http
 *
 * @author  Melech Mizrachi
 */
class Router implements RouterContract
{
    /**
     * @var \Valkyrja\Application
     */
    protected $app;

    /**
     * Application routes.
     *
     * @var array
     */
    protected $routes = [
        'simple'  => [
            self::GET    => [],
            self::POST   => [],
            self::PUT    => [],
            self::PATCH  => [],
            self::DELETE => [],
            self::HEAD   => [],
        ],
        'dynamic' => [
            self::GET    => [],
            self::POST   => [],
            self::PUT    => [],
            self::PATCH  => [],
            self::DELETE => [],
            self::HEAD   => [],
        ],
    ];

    /**
     * Router constructor.
     *
     * @param \Valkyrja\Application $application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }

    /**
     * Set a single route.
     *
     * @param string         $method    The method type (GET, POST, PUT, PATCH, DELETE, HEAD)
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    public function addRoute($method, $path, $handler, $isDynamic = false)
    {
        if (!in_array(
            $method,
            [
                self::GET,
                self::POST,
                self::PUT,
                self::PATCH,
                self::DELETE,
                self::HEAD,
            ]
        )
        ) {
            throw new \Exception('Invalid method type for route: ' . $path);
        }

        $isArray = is_array($handler);

        $name = ($isArray && isset($handler['as']))
            ? $handler['as']
            : $path;

        if (is_callable($handler)) {
            $action = $handler;
            $controller = false;
            $injectable = [];
        }
        else {
            $controller = ($isArray && isset($handler['controller']))
                ? $handler['controller']
                : false;

            $action = ($isArray && isset($handler['action']))
                ? $handler['action']
                : false;

            $injectable = ($isArray && isset($handler['injectable']))
                ? $handler['injectable']
                : [];

            if (!$action) {
                throw new \Exception('No action or handler set for route: ' . $path);
            }
        }

        $route = [
            'path'       => $path,
            'as'         => $name,
            'controller' => $controller,
            'action'     => $action,
            'injectable' => $injectable,
        ];

        // Set the route
        if ($isDynamic) {
            $this->routes['dynamic'][$method][$path] = $route;
        }
        else {
            $this->routes['simple'][$method][$path] = $route;
        }
    }

    /**
     * Helper function to set a GET addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    function get($path, $handler, $isDynamic = false)
    {
        $this->addRoute(static::GET, $path, $handler, $isDynamic);
    }

    /**
     * Helper function to set a POST addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    function post($path, $handler, $isDynamic = false)
    {
        $this->addRoute(static::POST, $path, $handler, $isDynamic);
    }

    /**
     * Helper function to set a PUT addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    function put($path, $handler, $isDynamic = false)
    {
        $this->addRoute(static::PUT, $path, $handler, $isDynamic);
    }

    /**
     * Helper function to set a PATCH addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    function patch($path, $handler, $isDynamic = false)
    {
        $this->addRoute(static::PATCH, $path, $handler, $isDynamic);
    }

    /**
     * Helper function to set a DELETE addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    function delete($path, $handler, $isDynamic = false)
    {
        $this->addRoute(static::DELETE, $path, $handler, $isDynamic);
    }

    /**
     * Helper function to set a HEAD addRoute.
     *
     * @param string         $path      The path to set
     * @param \Closure|array $handler   The closure or array of options
     * @param bool           $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    public function head($path, $handler, $isDynamic = false)
    {
        $this->addRoute(static::HEAD, $path, $handler, $isDynamic);
    }

    /**
     * Set routes from a given array of routes.
     *
     * @param array $routes The routes to set
     *
     * @return void
     */
    public function setRoutes(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Dispatch the route and find a match.
     *
     * @return \Valkyrja\Contracts\View\View|\Valkyrja\Http\Response|string
     *
     * @throws \Exception
     */
    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];
        $arguments = [];
        $route = false;
        $matches = false;

        if (isset($this->routes['simple'][$requestMethod][$requestUri])) {
            $route = $this->routes['simple'][$requestMethod][$requestUri];
        }

        foreach ($this->routes['dynamic'][$requestMethod] as $path => $dynamicRoute) {
            if (preg_match('/^' . $path . '$/', $requestUri, $matches)) {
                $route = $dynamicRoute;
            }
        }

        if ($route) {
            $action = $route['action'];

            foreach ($route['injectable'] as $injectable) {
                $arguments[] = $this->app->container($injectable);
            }

            if ($matches && is_array($matches)) {
                foreach ($matches as $index => $match) {
                    if ($index === 0) {
                        continue;
                    }

                    $arguments[] = $match;
                }
            }

            if (is_callable($action)) {
                return call_user_func_array($action, $arguments);
            }

            $controller = $this->app->container($route['controller']);

            if (!$controller instanceof \Valkyrja\Http\Controller) {
                throw new \Exception(
                    'Invalid controller for route : ' . $route['path'] . ' Controller -> ' . $route['controller']
                );
            }

            if (!is_callable(
                [
                    $controller,
                    $action,
                ]
            )
            ) {
                throw new \Exception(
                    'Action does not exist in controller for route : '
                    . $route['path']
                    . $route['controller']
                    . '@'
                    . $route['action']
                );
            }

            return call_user_func_array(
                [
                    $controller,
                    $action,
                ],
                $arguments
            );
        }

        return false;
    }
}
