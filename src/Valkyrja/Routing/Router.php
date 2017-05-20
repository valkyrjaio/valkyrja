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

use Valkyrja\Contracts\Application as ApplicationContract;
use Valkyrja\Contracts\Http\Request as RequestContract;
use Valkyrja\Contracts\Http\Response as ResponseContract;
use Valkyrja\Contracts\Routing\Annotations\RouteAnnotations as RouteAnnotationsContract;
use Valkyrja\Contracts\Routing\Router as RouterContract;
use Valkyrja\Contracts\View\View as ViewContract;
use Valkyrja\Http\Exceptions\NotFoundHttpException;
use Valkyrja\Http\RequestMethod;
use Valkyrja\Http\ResponseCode;
use Valkyrja\Routing\Exceptions\InvalidRouteName;

/**
 * Class Router.
 *
 * @author Melech Mizrachi
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
     * Whether route's have been setup yet.
     *
     * @var bool
     */
    protected static $setup = false;

    /**
     * The routes.
     *
     * @var \Valkyrja\Routing\Route[]
     */
    protected static $routes = [];

    /**
     * The static routes.
     *
     * @var string[]
     */
    protected static $staticRoutes = [];

    /**
     * The dynamic routes.
     *
     * @var string[]
     */
    protected static $dynamicRoutes = [];

    /**
     * The named routes.
     *
     * @var string[]
     */
    protected static $namedRoutes = [];

    /**
     * Router constructor.
     *
     * @param \Valkyrja\Contracts\Application $application The application
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
     * @throws \InvalidArgumentException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidClosureException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidFunctionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidMethodException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidPropertyException
     *
     * @return void
     */
    public function addRoute(Route $route): void
    {
        $this->app->dispatcher()->verifyDispatch($route);

        $route->setPath($this->validatePath($route->getPath()));
        // Ensure the request methods are set
        $route->getRequestMethods();

        // If this is a dynamic route
        if ($route->getDynamic()) {
            $this->setDynamicRoute($route);
            self::$dynamicRoutes[$route->getRegex()] = $route->getPath();
        } // Otherwise set it in the static routes array
        else {
            self::$staticRoutes[$route->getPath()] = true;

            $this->setNamedRoute($route);
        }

        self::$routes[$route->getPath()] = $route;
    }

    /**
     * Validate a path.
     *
     * @param string $path The path
     *
     * @return string
     */
    protected function validatePath(string $path): string
    {
        return '/' . trim($path, '/');
    }

    /**
     * Set a dynamic route's properties.
     *
     * @param \Valkyrja\Routing\Route $route The route
     *
     * @return void
     */
    protected function setDynamicRoute(Route $route): void
    {
        $parsedRoute = $this->app->pathParser()->parse($route->getPath());

        // Set the properties
        $route->setRegex($parsedRoute['regex']);
        $route->setParams($parsedRoute['params']);
        $route->setSegments($parsedRoute['segments']);

        $this->setNamedRoute($route);
    }

    /**
     * Set the named route.
     *
     * @param \Valkyrja\Routing\Route $route The route
     *
     * @return void
     */
    protected function setNamedRoute(Route $route): void
    {
        if ($route->getName()) {
            self::$namedRoutes[$route->getName()] = $route->getPath();
        }
    }

    /**
     * Helper function to set a GET addRoute.
     *
     * @param \Valkyrja\Routing\Route $route The route
     *
     * @throws \Exception
     *
     * @return void
     */
    public function get(Route $route): void
    {
        $route->setRequestMethods([RequestMethod::GET, RequestMethod::HEAD]);

        $this->addRoute($route);
    }

    /**
     * Helper function to set a POST addRoute.
     *
     * @param \Valkyrja\Routing\Route $route The route
     *
     * @throws \Exception
     *
     * @return void
     */
    public function post(Route $route): void
    {
        $route->setRequestMethods([RequestMethod::POST]);

        $this->addRoute($route);
    }

    /**
     * Helper function to set a PUT addRoute.
     *
     * @param \Valkyrja\Routing\Route $route The route
     *
     * @throws \Exception
     *
     * @return void
     */
    public function put(Route $route): void
    {
        $route->setRequestMethods([RequestMethod::PUT]);

        $this->addRoute($route);
    }

    /**
     * Helper function to set a PATCH addRoute.
     *
     * @param \Valkyrja\Routing\Route $route The route
     *
     * @throws \Exception
     *
     * @return void
     */
    public function patch(Route $route): void
    {
        $route->setRequestMethods([RequestMethod::PATCH]);

        $this->addRoute($route);
    }

    /**
     * Helper function to set a DELETE addRoute.
     *
     * @param \Valkyrja\Routing\Route $route The route
     *
     * @throws \Exception
     *
     * @return void
     */
    public function delete(Route $route): void
    {
        $route->setRequestMethods([RequestMethod::DELETE]);

        $this->addRoute($route);
    }

    /**
     * Helper function to set a HEAD addRoute.
     *
     * @param \Valkyrja\Routing\Route $route The route
     *
     * @throws \Exception
     *
     * @return void
     */
    public function head(Route $route): void
    {
        $route->setRequestMethods([RequestMethod::HEAD]);

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
     * Get a route by name.
     *
     * @param string $name The name of the route to get
     *
     * @throws \Valkyrja\Routing\Exceptions\InvalidRouteName
     *
     * @return \Valkyrja\Routing\Route
     */
    public function route(string $name): Route
    {
        // If no route was found
        if (! $this->routeIsset($name)) {
            throw new InvalidRouteName($name);
        }

        return self::$routes[self::$namedRoutes[$name]];
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
        return isset(self::$namedRoutes[$name]);
    }

    /**
     * Get a route url by name.
     *
     * @param string $name     The name of the route to get
     * @param array  $data     [optional] The route data if dynamic
     * @param bool   $absolute [optional] Whether this url should be absolute
     *
     * @throws \Valkyrja\Routing\Exceptions\InvalidRouteName
     *
     * @return string
     */
    public function routeUrl(string $name, array $data = null, bool $absolute = null): string
    {
        // Get the matching route
        $route = $this->route($name);
        // Set the host to use if this is an absolute url
        // or the config is set to always use absolute urls
        // or the route is secure (needs https:// appended)
        $host = $absolute || $this->app->config()->routing->useAbsoluteUrls || $route->getSecure()
            ? $this->routeHost($route)
            : '';
        // Get the path from the generator
        $path = $route->getSegments()
            ? $this->app->pathGenerator()->parse($route->getSegments(), $data, $route->getParams())
            : $route->getPath();

        return $host . $this->validateRouteUrl($path);
    }

    /**
     * Get a route's host.
     *
     * @param \Valkyrja\Routing\Route $route The route
     *
     * @return string
     */
    protected function routeHost(Route $route): string
    {
        return 'http'
            . ($route->getSecure() ? 's' : '')
            . '://'
            . request()->getHttpHost();
    }

    /**
     * Validate the route url.
     *
     * @param string $path The path
     *
     * @return string
     */
    protected function validateRouteUrl(string $path): string
    {
        // If the last character is not a slash and the config is set to ensure trailing slash
        if ($path[-1] !== '/' && $this->app->config()->routing->trailingSlash) {
            // add a trailing slash
            $path .= '/';
        }

        return $path;
    }

    /**
     * Get a route from a request.
     *
     * @param \Valkyrja\Contracts\Http\Request $request The request
     *
     * @throws \InvalidArgumentException
     *
     * @return \Valkyrja\Routing\Route
     */
    public function requestRoute(RequestContract $request):? Route
    {
        $requestMethod = $request->getMethod();
        $requestUri    = $request->getPathOnly();

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
     * @throws \InvalidArgumentException
     *
     * @return \Valkyrja\Routing\Route
     */
    public function matchRoute(string $path, string $method = RequestMethod::GET):? Route
    {
        // Validate the path
        $path  = $this->validatePath($path);
        $route = null;

        // Let's check if the route is set in the static routes
        if (isset(self::$staticRoutes[$path])) {
            $route = self::$routes[$path];

            if (in_array($method, $route->getRequestMethods(), true)) {
                return $route;
            }
        }

        // Attempt to find a match using dynamic routes that are set
        foreach (self::$dynamicRoutes as $regex => $dynamicRoute) {
            // If the preg match is successful, we've found our route!
            /* @var array $matches */
            if (preg_match($regex, $path, $matches)) {
                // Clone the route to avoid changing the one set in the master array
                $dynamicRoute = clone self::$routes[$dynamicRoute];
                // The first match is the path itself
                unset($matches[0]);

                // Iterate through the matches
                foreach ($matches as $key => $match) {
                    // If there is no match (middle of regex optional group)
                    if (! $match) {
                        // Set the value to null so the controller's action
                        // can use the default it sets
                        $matches[$key] = null;
                    }
                }

                // Set the matches
                $dynamicRoute->setMatches($matches);

                if (in_array($method, $dynamicRoute->getRequestMethods(), false)) {
                    return $dynamicRoute;
                }
            }
        }

        return $route;
    }

    /**
     * Determine if a uri is valid.
     *
     * @param string $uri The uri to check
     *
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    public function isInternalUri(string $uri): bool
    {
        // Replace the scheme if it exists
        $uri = str_replace(['http://', 'https://'], '', $uri);
        // Get only the path (full string from the first slash to the end of the path)
        $uri = (string) substr($uri, strpos($uri, '/'), count($uri));

        // Try to match the route
        $route = $this->matchRoute($uri);

        return $route instanceof Route;
    }

    /**
     * Dispatch the route and find a match.
     *
     * @param \Valkyrja\Contracts\Http\Request $request The request
     *
     * @throws \InvalidArgumentException
     * @throws \Valkyrja\Http\Exceptions\NotFoundHttpException
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function dispatch(RequestContract $request): ResponseContract
    {
        // Check the returned route
        if (null === $route = $this->requestRoute($request)) {
            // If it was null throw a not found exception
            throw new NotFoundHttpException();
        }

        // If the route is secure and the current request is not secure
        if ($route->getSecure() && ! $request->isSecure()) {
            // Throw the redirect to the secure path
            return $this->app->redirect()->secure($request->getPath());
        }

        // Attempt to dispatch the route using any one of the callable options
        $dispatch = $this->app->dispatcher()->dispatchCallable($route, $route->getMatches());

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

    /**
     * Setup routes.
     *
     * @throws \InvalidArgumentException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidClosureException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidFunctionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidMethodException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidPropertyException
     *
     * @return void
     */
    public function setup(): void
    {
        // If route's have already been setup, no need to do it again
        if (self::$setup) {
            return;
        }

        self::$setup = true;

        // If the application should use the routes cache file
        if ($this->app->config()->routing->useCacheFile) {
            // Set the application routes with said file
            $routesCache = unserialize(base64_decode(require $this->app->config()->routing->cacheFilePath));

            self::$routes        = $routesCache['routes'];
            self::$staticRoutes  = $routesCache['staticRoutes'];
            self::$dynamicRoutes = $routesCache['dynamicRoutes'];
            self::$namedRoutes   = $routesCache['namedRoutes'];

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
        require $this->app->config()->routing->filePath;
    }

    /**
     * Setup annotated routes.
     *
     * @throws \InvalidArgumentException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidClosureException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidFunctionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidMethodException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidPropertyException
     *
     * @return void
     */
    protected function setupAnnotatedRoutes(): void
    {
        /** @var RouteAnnotationsContract $routeAnnotations */
        $routeAnnotations = $this->app->container()->get(RouteAnnotationsContract::class);

        // Get all the annotated routes from the list of controllers
        $routes = $routeAnnotations->getRoutes(...$this->app->config()->routing->controllers);

        // Iterate through the routes
        foreach ($routes as $route) {
            // Set the route
            $this->addRoute($route);
        }
    }

    /**
     * Get a cacheable representation of the data.
     *
     * @throws \InvalidArgumentException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidClosureException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidFunctionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidMethodException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidPropertyException
     *
     * @return array
     */
    public function getCacheable(): array
    {
        self::$routes        = [];
        self::$staticRoutes  = [];
        self::$dynamicRoutes = [];
        self::$namedRoutes   = [];

        // The original use cache file value (may not be using cache to begin with)
        $originalUseCacheFile = $this->app->config()->routing->useCacheFile;
        // Avoid using the cache file we already have
        $this->app->config()->routing->useCacheFile = false;
        self::$setup                                = false;
        $this->setup();

        // Reset the use cache file value
        $this->app->config()->routing->useCacheFile = $originalUseCacheFile;

        return [base64_encode(serialize([
            'routes'        => self::$routes,
            'staticRoutes'  => self::$staticRoutes,
            'dynamicRoutes' => self::$dynamicRoutes,
            'namedRoutes'   => self::$namedRoutes,
        ]))];
    }
}
