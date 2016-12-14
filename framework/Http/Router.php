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

use Closure;

use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Http\Controller as ControllerContract;
use Valkyrja\Contracts\Http\Response as ResponseContract;
use Valkyrja\Contracts\Http\Router as RouterContract;
use Valkyrja\Contracts\View\View as ViewContract;
use Valkyrja\Http\Exceptions\InvalidControllerException;
use Valkyrja\Http\Exceptions\InvalidMethodTypeException;
use Valkyrja\Http\Exceptions\NonExistentActionException;

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
     * Application.
     *
     * @var \Valkyrja\Contracts\Application
     */
    protected $app;

    /**
     * Application routes.
     *
     * @var array
     */
    protected $routes = [
        'simple'  => [
            RequestMethod::GET    => [],
            RequestMethod::POST   => [],
            RequestMethod::PUT    => [],
            RequestMethod::PATCH  => [],
            RequestMethod::DELETE => [],
            RequestMethod::HEAD   => [],
        ],
        'dynamic' => [
            RequestMethod::GET    => [],
            RequestMethod::POST   => [],
            RequestMethod::PUT    => [],
            RequestMethod::PATCH  => [],
            RequestMethod::DELETE => [],
            RequestMethod::HEAD   => [],
        ],
    ];

    /**
     * Router constructor.
     *
     * @param \Valkyrja\Contracts\Application $application
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
     * @throws \Valkyrja\Http\Exceptions\InvalidMethodTypeException
     * @throws \Valkyrja\Http\Exceptions\NonExistentActionException
     */
    public function addRoute(string $method, string $path, $handler, bool $isDynamic = false) : void
    {
        // Ensure the method specified is allowed
        if (! in_array(
            $method,
            [
                RequestMethod::GET,
                RequestMethod::POST,
                RequestMethod::PUT,
                RequestMethod::PATCH,
                RequestMethod::DELETE,
                RequestMethod::HEAD,
            ],
            true
        )
        ) {
            throw new InvalidMethodTypeException('Invalid method type for route: ' . $path);
        }

        $isArray = is_array($handler);

        $name = ($isArray && isset($handler['as']))
            ? $handler['as']
            : $path;

        if ($handler instanceof Closure) {
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

            if (! $action) {
                throw new NonExistentActionException('No action or handler set for route: ' . $path);
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
    public function get(string $path, $handler, bool $isDynamic = false) : void
    {
        $this->addRoute(RequestMethod::GET, $path, $handler, $isDynamic);
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
    public function post(string $path, $handler, bool $isDynamic = false) : void
    {
        $this->addRoute(RequestMethod::POST, $path, $handler, $isDynamic);
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
    public function put(string $path, $handler, bool $isDynamic = false) : void
    {
        $this->addRoute(RequestMethod::PUT, $path, $handler, $isDynamic);
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
    public function patch(string $path, $handler, bool $isDynamic = false) : void
    {
        $this->addRoute(RequestMethod::PATCH, $path, $handler, $isDynamic);
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
    public function delete(string $path, $handler, bool $isDynamic = false) : void
    {
        $this->addRoute(RequestMethod::DELETE, $path, $handler, $isDynamic);
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
    public function head(string $path, $handler, bool $isDynamic = false) : void
    {
        $this->addRoute(RequestMethod::HEAD, $path, $handler, $isDynamic);
    }

    /**
     * Set routes from a given array of routes.
     *
     * @param array $routes The routes to set
     *
     * @return void
     */
    public function setRoutes(array $routes) : void
    {
        $this->routes = $routes;
    }

    /**
     * Dispatch the route and find a match.
     *
     * @return \Valkyrja\Contracts\Http\Response
     *
     * @throws \Valkyrja\Contracts\Exceptions\HttpException
     * @throws \Valkyrja\Http\Exceptions\InvalidControllerException
     * @throws \Valkyrja\Http\Exceptions\NonExistentActionException
     */
    public function dispatch() : ResponseContract
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];
        $arguments = [];
        $hasArguments = false;
        $route = [];
        $matches = false;

        // Let's check if the route is set in the simple routes
        if (isset($this->routes['simple'][$requestMethod][$requestUri])) {
            $route = $this->routes['simple'][$requestMethod][$requestUri];
        }
        // elseif (isset($this->routes['simple'][$requestMethod][substr($requestUri, 0, -1)])) {
        //     $route = $this->routes['simple'][$requestMethod][substr($requestUri, 0, -1)];
        // }
        else {
            // Attempt to find a match using dynamic routes that are set
            foreach ($this->routes['dynamic'][$requestMethod] as $path => $dynamicRoute) {
                // If the perg match is successful, we've found our route!
                if (preg_match('/^' . $path . '$/', $requestUri, $matches)) {
                    $route = $dynamicRoute;
                }
            }
        }

        // If no route is found
        if (! $route) {
            $this->app->abort(404);
        }

        // Set the action from the route
        $action = $route['action'];

        // If there are injectable items defined for this route
        if ($route['injectable']) {
            $hasArguments = true;

            // Check for any injectables that have been set on the route
            foreach ($route['injectable'] as $injectable) {
                // Set these as the first set of arguments to pass to the action
                $arguments[] = $this->app->container()->get($injectable);
            }
        }

        // If there were matches from the dynamic route
        if ($matches && is_array($matches)) {
            $hasArguments = true;

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
        if ($action instanceof Closure) {
            // If there are arguments and they should be passed in individually
            if ($hasArguments) {
                // Call it and set it as our dispatch
                $dispatch = $action(...$arguments);
            }
            // Otherwise no arguments just call the action
            else {
                // Call it and set it as our dispatch
                $dispatch = $action();
            }
        }
        // Otherwise the action should be a method in a controller
        else {
            // Set the controller through the container
            $controller = $this->app->container()->get($route['controller']);

            // Let's make sure the controller is a controller
            if (! $controller instanceof ControllerContract) {
                throw new InvalidControllerException(
                    'Invalid controller for route : '
                    . $route['path']
                    . ' Controller -> '
                    . $route['controller']
                );
            }

            // Let's check the action method is callable before proceeding
            if (! is_callable(
                [
                    $controller,
                    $action,
                ]
            )
            ) {
                throw new NonExistentActionException(
                    'Action does not exist in controller for route : '
                    . $route['path']
                    . $route['controller']
                    . '@'
                    . $route['action']
                );
            }

            // If there are arguments
            if ($hasArguments) {
                // Set the dispatch as the controller action
                $dispatch = $controller->$action(...$arguments);
            }
            // Otherwise no arguments just call the action
            else {
                // Set the dispatch as the controller action
                $dispatch = $controller->$action();
            }

            $controller->after();
        }

        // If the dispatch failed, 404
        if (! $dispatch) {
            $this->app->abort(404);
        }

        // If the dispatch is a Response, send it
        if ($dispatch instanceof ResponseContract) {
            return $dispatch;
        }
        // If the dispatch is a View, render it
        //  then echo it out as a string
        else if ($dispatch instanceof ViewContract) {
            return $this->app->response($dispatch->render());
        }
        // Otherwise echo it out as a string
        else {
            return $this->app->response($dispatch);
        }
    }
}
