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

use Exception;

use Valkyrja\Contracts\Http\Controller as ControllerContract;
use Valkyrja\Contracts\Http\Response as ResponseContract;
use Valkyrja\Contracts\Http\Router as RouterContract;
use Valkyrja\Contracts\View\View as ViewContract;
use Valkyrja\Support\Helpers;

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
    public function addRoute(string $method, string $path, $handler, bool $isDynamic = false) // : void
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
            throw new Exception('Invalid method type for route: ' . $path);
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
                throw new Exception('No action or handler set for route: ' . $path);
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
    function get(string $path, $handler, bool $isDynamic = false) // : void
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
    function post(string $path, $handler, bool $isDynamic = false) // : void
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
    function put(string $path, $handler, bool $isDynamic = false) // : void
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
    function patch(string $path, $handler, bool $isDynamic = false) // : void
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
    function delete(string $path, $handler, bool $isDynamic = false) // : void
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
    public function head(string $path, $handler, bool $isDynamic = false) // : void
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
    public function setRoutes(array $routes) // : void
    {
        $this->routes = $routes;
    }

    /**
     * Dispatch the route and find a match.
     *
     * @return void
     *
     * @throws \Exception
     */
    public function dispatch() // : void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];
        $arguments = [];
        $route = false;
        $matches = false;
        $dispatch = false;
        // Whether to use arguments as an array
        $useArrayArgs = Helpers::config()->routing->useArrayArgs ?? false;

        // Let's check if the route is set in the simple routes
        if (isset($this->routes['simple'][$requestMethod][$requestUri])) {
            $route = $this->routes['simple'][$requestMethod][$requestUri];
        }

        // If the route wasn't already found
        if (!$route) {
            // Attempt to find a match using dynamic routes that are set
            foreach ($this->routes['dynamic'][$requestMethod] as $path => $dynamicRoute) {
                // If the perg match is successful, we've found our route!
                if (preg_match('/^' . $path . '$/', $requestUri, $matches)) {
                    $route = $dynamicRoute;
                }
            }
        }

        // If a route has been found
        if ($route) {
            // Se the action from the route
            $action = $route['action'];

            // Check for any injectables that have been set on the route
            foreach ($route['injectable'] as $injectable) {
                // Set these as the first set of arguments to pass to the action
                $arguments[] = Helpers::container()->get($injectable);
            }

            // If there were matches from the dynamic route
            if ($matches && is_array($matches)) {
                // Iterate through the matches
                foreach ($matches as $index => $match) {
                    // Disregard the first match (which is the route itself)
                    if ($index === 0) {
                        continue;
                    }

                    // Set the remaining arguments to pass to the action with those matches
                    $arguments[] = $match;
                }
            }

            // If the action is a callable closure
            if (is_callable($action)) {
                // Check if we should use arguments as an array
                if ($useArrayArgs) {
                    // Call it an set is as our dispatch
                    $dispatch = $action($arguments);
                }
                else {
                    // Call it and set it as our dispatch
                    $dispatch = call_user_func_array($action, $arguments);
                }
            }
            // Otherwise the action should be a method in a controller
            else {
                // Set the controller through the container
                $controller = Helpers::container()->get($route['controller']);

                // Let's make sure the controller is a controller
                if (!$controller instanceof ControllerContract) {
                    throw new Exception(
                        'Invalid controller for route : ' . $route['path'] . ' Controller -> ' . $route['controller']
                    );
                }

                // Let's check the action method is callable before proceeding
                if (!is_callable(
                    [
                        $controller,
                        $action,
                    ]
                )
                ) {
                    throw new Exception(
                        'Action does not exist in controller for route : '
                        . $route['path']
                        . $route['controller']
                        . '@'
                        . $route['action']
                    );
                }

                // Check if we should use arguments as an array
                if ($useArrayArgs) {
                    // Set the dispatch as the controller action
                    $dispatch = $controller->$action($arguments);
                }
                else {
                    // Set the dispatch as the controller action
                    $dispatch = call_user_func_array(
                        [
                            $controller,
                            $action,
                        ],
                        $arguments
                    );
                }

                $controller->after();
            }
        }

        // If the dispatch failed, 404
        if (!$dispatch) {
            Helpers::abort(404);
        }

        // If the dispatch is a Response, send it
        if ($dispatch instanceof ResponseContract) {
            $dispatch->send();
        }
        // If the dispatch is a View, render it
        //  then echo it out as a string
        else if ($dispatch instanceof ViewContract) {
            echo (string) $dispatch->render();
        }
        // Otherwise echo it out as a string
        else {
            echo (string) $dispatch;
        }
    }
}
