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

use Valkyrja\Contracts\Application as ApplicationContract;
use Valkyrja\Contracts\Http\Controller as ControllerContract;
use Valkyrja\Contracts\Http\Request as RequestContract;
use Valkyrja\Contracts\Http\Response as ResponseContract;
use Valkyrja\Contracts\Routing\Annotations\RouteParser as RouteParserContract;
use Valkyrja\Contracts\Routing\Router as RouterContract;
use Valkyrja\Contracts\View\View as ViewContract;
use Valkyrja\Http\Exceptions\NotFoundHttpException;
use Valkyrja\Routing\Annotations\RouteAnnotations;
use Valkyrja\Routing\Exceptions\InvalidControllerException;
use Valkyrja\Routing\Exceptions\InvalidRouteName;
use Valkyrja\Routing\Exceptions\NonExistentActionException;
use Valkyrja\Http\RequestMethod;
use Valkyrja\Http\ResponseCode;
use Valkyrja\Routing\Exceptions\InvalidHandlerException;

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
     * The static routes type.
     *
     * @constant string
     */
    protected const STATIC_ROUTES_TYPE = 'static';

    /**
     * The dynamic routes type.
     */
    protected const DYNAMIC_ROUTES_TYPE = 'dynamic';

    /**
     * The name routes type.
     */
    protected const NAME_ROUTES_TYPE = 'name';

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
    protected static $routes = [
        self::STATIC_ROUTES_TYPE  => [
            RequestMethod::GET    => [],
            RequestMethod::POST   => [],
            RequestMethod::PUT    => [],
            RequestMethod::PATCH  => [],
            RequestMethod::DELETE => [],
            RequestMethod::HEAD   => [],
        ],
        self::DYNAMIC_ROUTES_TYPE => [
            RequestMethod::GET    => [],
            RequestMethod::POST   => [],
            RequestMethod::PUT    => [],
            RequestMethod::PATCH  => [],
            RequestMethod::DELETE => [],
            RequestMethod::HEAD   => [],
        ],
        self::NAME_ROUTES_TYPE    => [],
    ];

    /**
     * Router constructor.
     *
     * @param \Valkyrja\Contracts\Application $application
     */
    public function __construct(ApplicationContract $application)
    {
        $this->app = $application;
    }

    /**
     * Set a single route.
     *
     * @param \Valkyrja\Routing\Route $route The route
     *
     * @return void
     *
     * @throws \Valkyrja\Routing\Exceptions\NonExistentActionException
     */
    public function addRoute(Route $route): void
    {
        // Let's check the action method is callable before proceeding
        if (
            null === $route->getHandler()
            && ! is_callable(
                [
                    $route->getClass(),
                    $route->getMethod(),
                ]
            )
        ) {
            throw new NonExistentActionException(
                'Action does not exist in controller for route : '
                . $route->getPath()
                . $route->getClass()
                . '@'
                . $route->getMethod()
            );
        }

        $route->setPath('/' . trim($route->getPath(), '/'));

        // If this is a dynamic route
        if ($route->getDynamic()) {
            $this->setDynamicRouteProperties($route);

            // Set it in the dynamic routes array
            self::$routes[static::DYNAMIC_ROUTES_TYPE][$route->getRequestMethod()][$route->getPath()] = $route;
        }
        // Otherwise set it in the static routes array
        else {
            self::$routes[static::STATIC_ROUTES_TYPE][$route->getRequestMethod()][$route->getPath()] = $route;
        }

        if ($route->getName()) {
            self::$routes[static::NAME_ROUTES_TYPE][$route->getName()] = [
                $route->getDynamic() ? static::DYNAMIC_ROUTES_TYPE : static::STATIC_ROUTES_TYPE,
                $route->getRequestMethod(),
                $route->getPath(),
            ];
        }
    }

    /**
     * Set a dynamic route's properties.
     *
     * @param \Valkyrja\Routing\Route $route The route
     *
     * @return void
     */
    protected function setDynamicRouteProperties(Route $route): void
    {
        $path = $route->getPath();

        // Get all matches for {paramName} and {paramName:(validator)} in the path
        preg_match_all(
            '/' . static::VARIABLE_REGEX . '/x',
            $path,
            $params
        );
        /** @var array[] $params */

        // Run through all matches
        foreach ($params[0] as $key => $param) {
            // Check if a global regex alias was used
            switch ($params[2][$key]) {
                case 'num' :
                    $replacement = '(\d+)';
                    break;
                case 'slug' :
                    $replacement = '([a-zA-Z0-9-]+)';
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
                case 'alpha-num' :
                    $replacement = '([a-zA-Z0-9]+)';
                    break;
                case 'alpha-num-underscore' :
                    $replacement = '(\w+)';
                    break;
                default :
                    // Check if a regex was set for this match, otherwise use a wildcard all
                    $replacement = $params[2][$key] ?: '(.*)';
                    break;
            }

            // Replace the matches with a regex
            $path = str_replace($param, $replacement, $path);
        }

        $path = str_replace('/', '\/', $path);
        $path = '/^' . $path . '$/';
        $route->setPath($path);
        $route->setParams($params);
    }

    /**
     * Helper function to set a GET addRoute.
     *
     * @param \Valkyrja\Routing\Route $route The route
     *
     * @return void
     *
     * @throws \Exception
     */
    public function get(Route $route): void
    {
        $route->setRequestMethod(new RequestMethod(RequestMethod::GET));

        $this->addRoute($route);
    }

    /**
     * Helper function to set a POST addRoute.
     *
     * @param \Valkyrja\Routing\Route $route The route
     *
     * @return void
     *
     * @throws \Exception
     */
    public function post(Route $route): void
    {
        $route->setRequestMethod(new RequestMethod(RequestMethod::POST));

        $this->addRoute($route);
    }

    /**
     * Helper function to set a PUT addRoute.
     *
     * @param \Valkyrja\Routing\Route $route The route
     *
     * @return void
     *
     * @throws \Exception
     */
    public function put(Route $route): void
    {
        $route->setRequestMethod(new RequestMethod(RequestMethod::PUT));

        $this->addRoute($route);
    }

    /**
     * Helper function to set a PATCH addRoute.
     *
     * @param \Valkyrja\Routing\Route $route The route
     *
     * @return void
     *
     * @throws \Exception
     */
    public function patch(Route $route): void
    {
        $route->setRequestMethod(new RequestMethod(RequestMethod::PATCH));

        $this->addRoute($route);
    }

    /**
     * Helper function to set a DELETE addRoute.
     *
     * @param \Valkyrja\Routing\Route $route The route
     *
     * @return void
     *
     * @throws \Exception
     */
    public function delete(Route $route): void
    {
        $route->setRequestMethod(new RequestMethod(RequestMethod::DELETE));

        $this->addRoute($route);
    }

    /**
     * Helper function to set a HEAD addRoute.
     *
     * @param \Valkyrja\Routing\Route $route The route
     *
     * @return void
     *
     * @throws \Exception
     */
    public function head(Route $route): void
    {
        $route->setRequestMethod(new RequestMethod(RequestMethod::HEAD));

        $this->addRoute($route);
    }

    /**
     * Get all routes set by the application.
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return self::$routes;
    }

    /**
     * Get routes by method type.
     *
     * @param string $method The method type of get
     * @param string $type   [optional] The type of routes (static/dynamic)
     *
     * @return \Valkyrja\Routing\Route[]
     */
    protected function getRoutesByMethod(string $method, string $type = self::STATIC_ROUTES_TYPE): array
    {
        return self::$routes[$type][$method];
    }

    /**
     * Set routes from a given array of routes.
     *
     * @param array $routes The routes to set
     *
     * @return void
     */
    public function setRoutes(array $routes): void
    {
        self::$routes = $routes;
    }

    /**
     * Setup routes.
     *
     * @return void
     *
     * @throws \Valkyrja\Routing\Exceptions\NonExistentActionException
     */
    public function setup(): void
    {
        // If the application should use the routes cache file
        if ($this->app->config()->routing->useRoutesCacheFile) {
            // Set the application routes with said file
            self::$routes = require $this->app->config()->routing->routesCacheFile;

            // Then return out of routes setup
            return;
        }

        // If annotations are enabled and routing should use annotations
        if ($this->app->config()->routing->useAnnotations && $this->app->config()->annotations->enabled) {
            // Setup annotated routes
            $this->setupAnnotatedRoutes();

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
     * Setup annotated routes.
     *
     * @return void
     *
     * @throws \Valkyrja\Routing\Exceptions\NonExistentActionException
     */
    protected function setupAnnotatedRoutes(): void
    {
        // The routes annotations parser
        /** @var RouteParserContract $parser */
        $parser = $this->app->container()->get(RouteParserContract::class);

        $routeAnnotations = new RouteAnnotations($parser);

        $routes = $routeAnnotations->getRoutes(...$this->app->config()->routing->controllers);

        // Iterate through the routes
        foreach ($routes as $route) {
            // Set the route
            $this->addRoute($route);
        }
    }

    /**
     * Get a route by name.
     *
     * @param string $name The name of the route to get
     *
     * @return \Valkyrja\Routing\Route
     *
     * @throws \Valkyrja\Routing\Exceptions\InvalidRouteName
     */
    public function route(string $name): Route
    {
        // If no route was found
        if (! $this->routeIsset($name)) {
            throw new InvalidRouteName($name);
        }

        $routeName = self::$routes[static::NAME_ROUTES_TYPE][$name];

        return self::$routes[$routeName[0]][$routeName[1]][$routeName[2]];
    }

    /**
     * Determine whether a route name exists.
     *
     * @param string $name The name of the route
     *
     * @return bool
     */
    public function routeIsset(string $name): bool
    {
        return isset(self::$routes[static::NAME_ROUTES_TYPE][$name]);
    }

    /**
     * Get a route url by name.
     *
     * @param string $name The name of the route to get
     * @param array  $data [optional] The route data if dynamic
     *
     * @return string
     *
     * @throws \Valkyrja\Routing\Exceptions\InvalidRouteName
     */
    public function routeUrl(string $name, array $data = []): string
    {
        // Get the matching route
        $route = $this->route($name);

        // Set the path as the route's path
        $path = $route->getPath();

        // If there is data
        if ($data) {
            // Get the route's params
            /** @var array[] $params */
            $params = $route->getParams();

            // Iterate through all the prams
            foreach ($params[0] as $key => $param) {
                // Set the path by replacing the params with the data arguments
                $path = str_replace($param, $data[$params[1][$key]], $path);
            }

            return $path;
        }

        return $path;
    }

    /**
     * Get a route from a request.
     *
     * @param \Valkyrja\Contracts\Http\Request $request The request
     *
     * @return \Valkyrja\Routing\Route
     *
     * @throws \Valkyrja\Http\Exceptions\NotFoundHttpException
     */
    public function requestRoute(RequestContract $request): Route
    {
        $requestMethod = $request->getMethod();
        $requestUri = $request->getPathOnly();

        // Decode the request uri
        $requestUri = rawurldecode($requestUri);

        return $this->matchRoute($requestUri, $requestMethod);
    }

    /**
     * Get a route by path.
     *
     * @param string $path   The path
     * @param string $method [optional] The method type of get
     *
     * @return \Valkyrja\Routing\Route
     *
     * @throws \Valkyrja\Http\Exceptions\NotFoundHttpException
     */
    public function matchRoute(string $path, string $method = RequestMethod::GET): Route
    {
        $path = '/' . trim($path, '/');

        // Let's check if the route is set in the static routes
        if (isset(self::$routes[static::STATIC_ROUTES_TYPE][$method][$path])) {
            return self::$routes[static::STATIC_ROUTES_TYPE][$method][$path];
        }

        // Attempt to find a match using dynamic routes that are set
        foreach ($this->getRoutesByMethod($method, static::DYNAMIC_ROUTES_TYPE) as $pathIndex => $dynamicRoute) {
            // If the preg match is successful, we've found our route!
            if (preg_match($pathIndex, $path, $matches)) {
                $dynamicRoute->setMatches($matches);

                return $dynamicRoute;
            }
        }

        throw new NotFoundHttpException();
    }

    /**
     * Get a route's arguments.
     *
     * @param \Valkyrja\Routing\Route $route The route
     *
     * @return array
     */
    protected function getRouteArguments(Route $route): array
    {
        // Set the arguments to return
        $arguments = [];
        // Get the matches
        $matches = $route->getMatches();
        // The injectable objects
        $injectable = $route->getDependencies();

        // If there are injectable items defined for this route
        if ($injectable) {
            // Check for any injectable objects that have been set on the route
            foreach ($injectable as $injectableObject) {
                // Set these as the first set of arguments to pass to the action
                $arguments[] = $this->app->container()->get($injectableObject);
            }
        }

        // If there were matches from the dynamic route
        if ($matches) {
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

        return $arguments;
    }

    /**
     * Dispatch the route and find a match.
     *
     * @param \Valkyrja\Contracts\Http\Request $request The request
     *
     * @return \Valkyrja\Contracts\Http\Response
     *
     * @throws \Valkyrja\Http\Exceptions\NotFoundHttpException
     * @throws \Valkyrja\Routing\Exceptions\InvalidControllerException
     * @throws \Valkyrja\Routing\Exceptions\InvalidHandlerException
     */
    public function dispatch(RequestContract $request): ResponseContract
    {
        // Get the route from the request
        $route = $this->requestRoute($request);

        // If the route is secure and the current request is not secure
        if ($route->getSecure() && ! $request->isSecure()) {
            // Throw the redirect to the secure path
            return $this->app->redirect()->secure($request->getPath());
        }

        // If the route has an action and a controller
        if ($route->getMethod() && $route->getClass()) {
            return $this->dispatchAction($route);
        }

        return $this->dispatchHandler($route);
    }

    /**
     * Dispatch a route's handler.
     *
     * @param \Valkyrja\Routing\Route $route The route
     *
     * @return \Valkyrja\Contracts\Http\Response
     *
     * @throws \Valkyrja\Routing\Exceptions\InvalidHandlerException
     */
    public function dispatchHandler(Route $route): ResponseContract
    {
        // Get the route's arguments
        $arguments = $this->getRouteArguments($route);
        // Set the action as the route's handler
        $action = $route->getHandler();

        // If this action is not a closure
        if (! $action instanceof Closure) {
            throw new InvalidHandlerException(
                'Invalid handler for route : '
                . $route->getPath()
                . ' Name -> '
                . $route->getName()
            );
        }

        // If there are arguments and they should be passed in individually
        if ($arguments) {
            // Call it and set it as our dispatch
            $dispatch = $action(...$arguments);
        }
        // Otherwise no arguments just call the action
        else {
            // Call it and set it as our dispatch
            $dispatch = $action();
        }

        // Get a response from the dispatch results
        return $this->getResponseFromDispatch($dispatch);
    }

    /**
     * Dispatch a route's action.
     *
     * @param \Valkyrja\Routing\Route $route The route
     *
     * @return \Valkyrja\Contracts\Http\Response
     *
     * @throws \Valkyrja\Routing\Exceptions\InvalidControllerException
     */
    public function dispatchAction(Route $route): ResponseContract
    {
        // Get the route's arguments
        $arguments = $this->getRouteArguments($route);
        // Set the action as the route's handler
        $action = $route->getMethod();
        // Set the controller through the container
        $controller = $this->app->container()->get($route->getClass());

        // Let's make sure the controller is a controller
        if (! $controller instanceof ControllerContract) {
            throw new InvalidControllerException(
                'Invalid controller for route : '
                . $route->getPath()
                . ' Controller -> '
                . $route->getClass()
            );
        }

        // If there are arguments
        if ($arguments) {
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

        // Get a response from the dispatch results
        return $this->getResponseFromDispatch($dispatch);
    }

    /**
     * Get a response from a dispatch.
     *
     * @param mixed $dispatch The dispatch
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    protected function getResponseFromDispatch($dispatch): ResponseContract
    {
        // If the dispatch failed, 404
        if (! $dispatch) {
            $this->app->abort(ResponseCode::HTTP_NOT_FOUND);
        }

        // If the dispatch is a Response then simply return it
        if ($dispatch instanceof ResponseContract) {
            return $dispatch;
        }
        // If the dispatch is a View, render it then wrap it in a new response and return it
        if ($dispatch instanceof ViewContract) {
            return $this->app->response($dispatch->render());
        }

        // Otherwise its a string so wrap it in a new response and return it
        return $this->app->response((string) $dispatch);
    }
}
