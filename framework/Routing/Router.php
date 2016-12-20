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

use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Http\Controller as ControllerContract;
use Valkyrja\Contracts\Http\Response as ResponseContract;
use Valkyrja\Contracts\Routing\Router as RouterContract;
use Valkyrja\Contracts\View\View as ViewContract;
use Valkyrja\Http\Exceptions\InvalidControllerException;
use Valkyrja\Http\Exceptions\InvalidMethodTypeException;
use Valkyrja\Http\Exceptions\NonExistentActionException;
use Valkyrja\Http\RequestMethod;
use Valkyrja\Routing\Annotations\Parser;

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
        'static'  => [
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
     * @param string $method    The method type (GET, POST, PUT, PATCH, DELETE, HEAD)
     * @param string $path      The path to set
     * @param array  $options   The closure or array of options
     * @param bool   $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Valkyrja\Http\Exceptions\InvalidMethodTypeException
     * @throws \Valkyrja\Http\Exceptions\NonExistentActionException
     */
    public function addRoute(string $method, string $path, array $options, bool $isDynamic = false) : void
    {
        // Ensure the method specified is allowed
        if (! in_array(
            $method,
            RequestMethod::ACCEPTED_TYPES,
            true
        )
        ) {
            throw new InvalidMethodTypeException('Invalid method type for route: ' . $path);
        }

        // Let's check the action method is callable before proceeding
        if (! isset($options['handler']) && ! is_callable(
                [
                    $options['controller'],
                    $options['action'],
                ]
            )
        ) {
            throw new NonExistentActionException(
                'Action does not exist in controller for route : '
                . $path
                . $options['controller']
                . '@'
                . $options['action']
            );
        }

        // If all routes should have a trailing slash
        // and the route doesn't already end with a slash
        if ($this->app->config()->routing->trailingSlash && false === strpos($path, '/', -1)) {
            // Add a trailing slash
            $path .= '/';
        }

        $route = [
            'path'       => $path,
            'name'       => $options['name'] ?? $path,
            'controller' => $options['controller'] ?? false,
            'action'     => $options['action'] ?? false,
            'handler'    => $options['handler'] ?? false,
            'injectable' => $options['injectable'] ?? [],
        ];

        // If this is a dynamic route
        if ($isDynamic) {
            // Get all matches for {paramName} and {paramName:(validator)} in the path
            preg_match_all(
                '/\{\s*([a-zA-Z_][a-zA-Z0-9_-]*)\s*(?::\s*([^{}]*(?:\{(?-1)\}[^{}]*)*))?\}/',
                $path,
                $params
            );

            // Run through all matches
            foreach ($params[0] as $key => $param) {
                // Check if a regex was set for this match, otherwise use a wildcard all
                // $replacement = $params[2][$key] ?? '(.*)';

                switch ($params[2][$key]) {
                    case 'int' :
                        $replacement = '(\d+)';
                        break;
                    case 'alpha' :
                        $replacement = '([a-zA-Z]+)';
                        break;
                    case 'alpha-lowercase' :
                        $replacement = '([a-z]+)';
                        break;
                    case 'alpha-uppercase' :
                        $replacement = '([A-Z]+)';
                        break;
                    default :
                        $replacement = $params[2][$key] ?? '(.*)';
                        break;
                }

                // Replace the matches with a regex
                $path = str_replace($param, $replacement, $path);
            }

            $path = str_replace('/', '\/', $path);
            $path = '/^' . $path . '$/';
            $route['dynamicPath'] = $path;

            // Set it in the dynamic routes array
            $this->routes['dynamic'][$method][$path] = $route;
        }
        // Otherwise set it in the static routes array
        else {
            $this->routes['static'][$method][$path] = $route;
        }
    }

    /**
     * Helper function to set a GET addRoute.
     *
     * @param string $path      The path to set
     * @param array  $options   The closure or array of options
     * @param bool   $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    public function get(string $path, array $options, bool $isDynamic = false) : void
    {
        $this->addRoute(RequestMethod::GET, $path, $options, $isDynamic);
    }

    /**
     * Helper function to set a POST addRoute.
     *
     * @param string $path      The path to set
     * @param array  $options   The closure or array of options
     * @param bool   $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    public function post(string $path, array $options, bool $isDynamic = false) : void
    {
        $this->addRoute(RequestMethod::POST, $path, $options, $isDynamic);
    }

    /**
     * Helper function to set a PUT addRoute.
     *
     * @param string $path      The path to set
     * @param array  $options   The closure or array of options
     * @param bool   $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    public function put(string $path, array $options, bool $isDynamic = false) : void
    {
        $this->addRoute(RequestMethod::PUT, $path, $options, $isDynamic);
    }

    /**
     * Helper function to set a PATCH addRoute.
     *
     * @param string $path      The path to set
     * @param array  $options   The closure or array of options
     * @param bool   $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    public function patch(string $path, array $options, bool $isDynamic = false) : void
    {
        $this->addRoute(RequestMethod::PATCH, $path, $options, $isDynamic);
    }

    /**
     * Helper function to set a DELETE addRoute.
     *
     * @param string $path      The path to set
     * @param array  $options   The closure or array of options
     * @param bool   $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    public function delete(string $path, array $options, bool $isDynamic = false) : void
    {
        $this->addRoute(RequestMethod::DELETE, $path, $options, $isDynamic);
    }

    /**
     * Helper function to set a HEAD addRoute.
     *
     * @param string $path      The path to set
     * @param array  $options   The closure or array of options
     * @param bool   $isDynamic [optional] Does the route have dynamic parameters?
     *
     * @return void
     *
     * @throws \Exception
     */
    public function head(string $path, array $options, bool $isDynamic = false) : void
    {
        $this->addRoute(RequestMethod::HEAD, $path, $options, $isDynamic);
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
     * Get all routes set by the application.
     *
     * @return array
     */
    public function getRoutes() : array
    {
        return $this->routes;
    }

    /**
     * Get routes by method type.
     *
     * @param string $method The method type of get
     * @param string $type   [optional] The type of routes (static/dynamic)
     *
     * @return array
     */
    protected function getRoutesByMethod(string $method, string $type = 'static') : array
    {
        return $this->routes[$type][$method];
    }

    /**
     * Setup routes.
     *
     * @return void
     *
     * @throws \Valkyrja\Http\Exceptions\InvalidMethodTypeException
     * @throws \Valkyrja\Http\Exceptions\NonExistentActionException
     */
    public function setupRoutes() : void
    {
        // If the application should use the routes cache file
        if ($this->app->config()->routing->useRoutesCacheFile) {
            // Set the application routes with said file
            $this->routes = require $this->app->config()->routing->routesCacheFile;

            // Then return out of routes setup
            return;
        }

        // If annotations are enabled and routing should use annotations
        if ($this->app->config()->routing->useAnnotations && $this->app->config()->annotations->enabled) {
            $routes = [];
            $parser = new Parser();

            // Iterate through each controller
            foreach ($this->app->config()->routing->controllers as $controller) {
                // Get a reflection of the controller
                $reflection = new \ReflectionClass($controller);
                // Set an empty array for this controller to hold its defined routes
                $routes[$controller] = [];
                /** @var \Valkyrja\Routing\Annotations\Route[] $controllerRoutes */
                $controllerRoutes = $parser->getRouteAnnotations($reflection->getDocComment());
                // The controller base path
                $basePath = null;
                // The controller base name
                $baseName = null;

                // If an @Route annotation is set on the controller
                if ($controllerRoutes) {
                    // Set the base path for this controller
                    $basePath = $controllerRoutes[0]->get('path', null);
                    // Set the base name for this controller
                    $baseName = $controllerRoutes[0]->get('name', null);
                }

                // Iterate through all the methods in the controller
                foreach ($reflection->getMethods() as $method) {
                    // Get the @Route annotation for the method
                    $actionRoutes = $parser->getRouteAnnotations($method->getDocComment());

                    // Ensure a route was defined
                    if ($actionRoutes) {
                        // Set the route for this action
                        $routes[$controller][$method->getName()] = $actionRoutes;
                        // Setup to find any injectable objects through the service container
                        $injectable = [];

                        // Iterate through the method's parameters
                        foreach ($method->getParameters() as $parameter) {
                            // We only care for classes
                            if ($parameter->getClass()) {
                                // Set the injectable in the array
                                $injectable[] = $parameter->getClass()->getName();
                            }
                        }

                        /**
                         * Iterate through all the action's routes.
                         *
                         * @var \Valkyrja\Routing\Annotations\Route $route
                         */
                        foreach ($actionRoutes as $route) {
                            // Set the controller
                            $route->set('controller', $controller);
                            // Set the action
                            $route->set('action', $method->getName());
                            // Set the injectable objects
                            $route->set('injectable', $injectable);

                            // If there is a base path for this controller
                            if ($basePath) {
                                // Get the route's path
                                $path = $route->get('path');

                                // If this is the index
                                if ('/' === $path) {
                                    // Set to blank so the final path will be just the base path
                                    $path = '';
                                }

                                // Set the path to the base path and route path
                                $route->set('path', $basePath . $path);
                            }

                            // If there is a base name for this controller
                            if ($baseName) {
                                $name = $baseName . '.' . $route->get('name');

                                // Set the name to the base name and route name
                                $route->set('name', $name);
                            }
                        }
                    }
                }
            }

            /**
             * Iterate through the routes for each controller.
             *
             * @var string $controller
             * @var array  $controllerRoutes
             */
            foreach ($routes as $controller => $controllerRoutes) {
                /**
                 * Iterate through the actions.
                 *
                 * @var string $action
                 * @var array  $methodRoutes
                 */
                foreach ($controllerRoutes as $action => $methodRoutes) {
                    /**
                     * Iterate through the routes defined for each action.
                     *
                     * @var string                              $key
                     * @var \Valkyrja\Routing\Annotations\Route $route
                     */
                    foreach ($methodRoutes as $key => $route) {
                        // Set the route
                        $this->addRoute(
                            $route->get('method', RequestMethod::GET),
                            $route->get('path'),
                            $route->all(),
                            $route->get('dynamic')
                        );
                    }
                }
            }

            // If only annotations should be used for routing
            if ($this->app->config()->routing->useAnnotationsExclusively) {
                // Return to avoid loading routes file
                return;
            }
        }

        // Include the routes file
        // NOTE: Included if annotations are set or not due to possibility of routes being defined
        // within the controllers as well as within the routes file
        require $this->app->config()->routing->routesFile;
    }

    /**
     * Dispatch the route and find a match.
     *
     * @return \Valkyrja\Contracts\Http\Response
     *
     * @throws \Valkyrja\Contracts\Http\Exceptions\HttpException
     * @throws \Valkyrja\Http\Exceptions\InvalidControllerException
     */
    public function dispatch() : ResponseContract
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];
        $arguments = [];
        $hasArguments = false;
        $route = [];
        $matches = false;
        $dispatch = false;

        // Let's check if the route is set in the static routes
        if (isset($this->routes['static'][$requestMethod][$requestUri])) {
            $route = $this->routes['static'][$requestMethod][$requestUri];
        }
        // If trailing slashes and non trailing are allowed check it too
        elseif (
            $this->app->config()->routing->allowWithTrailingSlash &&
            isset($this->routes['static'][$requestMethod][substr($requestUri, 0, -1)])
        ) {
            $route = $this->routes['static'][$requestMethod][substr($requestUri, 0, -1)];
        }
        // Otherwise check dynamic routes for a match
        else {
            // Attempt to find a match using dynamic routes that are set
            foreach ($this->getRoutesByMethod($requestMethod, 'dynamic') as $path => $dynamicRoute) {
                // If the perg match is successful, we've found our route!
                if (preg_match($path, $requestUri, $matches)) {
                    $route = $dynamicRoute;
                }
            }
        }

        // If no route is found
        if (! $route) {
            // Launch the 404 and abort the app
            $this->app->abort(404);
        }

        // Set the action from the route to either the handler or controller action
        $action = $route['handler']
            ?: $route['action'];
        // The injectable objects
        $injectable = $route['injectable']
            ?: [];

        // If there are injectable items defined for this route
        if ($injectable) {
            // There are arguments to be had
            $hasArguments = true;

            // Check for any injectable objects that have been set on the route
            foreach ($injectable as $injectableObject) {
                // Set these as the first set of arguments to pass to the action
                $arguments[] = $this->app->container()->get($injectableObject);
            }
        }

        // If there were matches from the dynamic route
        if ($matches && is_array($matches)) {
            // There are arguments to be had
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
        elseif ($action && $route['controller']) {
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

            // Call the controller's after method
            $controller->after();
        }

        // If the dispatch failed, 404
        if (! $dispatch) {
            $this->app->abort(404);
        }

        // If the dispatch is a Response then simply return it
        if ($dispatch instanceof ResponseContract) {
            return $dispatch;
        }
        // If the dispatch is a View, render it then wrap it in a new response and return it
        else if ($dispatch instanceof ViewContract) {
            return $this->app->response($dispatch->render());
        }
        // Otherwise its a string so wrap it in a new response and return it
        else {
            return $this->app->response($dispatch);
        }
    }
}
