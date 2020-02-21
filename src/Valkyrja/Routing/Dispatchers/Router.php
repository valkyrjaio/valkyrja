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

namespace Valkyrja\Routing\Dispatchers;

use function count;
use Exception;
use InvalidArgumentException;
use function is_array;
use Valkyrja\Application\Application;
use Valkyrja\Config\Enums\ConfigKey;
use Valkyrja\Dispatcher\Exceptions\InvalidClosureException;
use Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;
use Valkyrja\Http\Enums\RequestMethod;
use Valkyrja\Http\Exceptions\NotFoundHttpException;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Routing\Cacheables\CacheableRouter;
use Valkyrja\Routing\Events\RouteMatched;
use Valkyrja\Routing\Exceptions\InvalidRouteName;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\RouteCollection;
use Valkyrja\Routing\RouteMatcher;
use Valkyrja\Routing\Router as RouterContract;
use Valkyrja\Support\Providers\Provides;
use Valkyrja\View\View;

/**
 * Class Router.
 *
 * @author Melech Mizrachi
 */
class Router implements RouterContract
{
    use Provides;
    use CacheableRouter;

    /**
     * Router constructor.
     *
     * @param Application $application The application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            RouterContract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            RouterContract::class,
            new static($app)
        );

        $app->router()->setup();
    }

    /**
     * Set a single route.
     *
     * @param Route $route The route
     *
     * @throws InvalidClosureException
     * @throws InvalidDispatchCapabilityException
     * @throws InvalidFunctionException
     * @throws InvalidMethodException
     * @throws InvalidPropertyException
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function addRoute(Route $route): void
    {
        // Set the route in the collection
        self::$collection->add($route);
    }

    /**
     * Helper function to set a GET addRoute.
     *
     * @param Route $route The route
     *
     * @throws Exception
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
     * @param Route $route The route
     *
     * @throws Exception
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
     * @param Route $route The route
     *
     * @throws Exception
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
     * @param Route $route The route
     *
     * @throws Exception
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
     * @param Route $route The route
     *
     * @throws Exception
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
     * @param Route $route The route
     *
     * @throws Exception
     *
     * @return void
     */
    public function head(Route $route): void
    {
        $route->setRequestMethods([RequestMethod::HEAD]);

        $this->addRoute($route);
    }

    /**
     * Helper function to set any request method addRoute.
     *
     * @param Route $route The route
     *
     * @throws Exception
     *
     * @return void
     */
    public function any(Route $route): void
    {
        $route->setRequestMethods(
            [
                RequestMethod::HEAD,
                RequestMethod::GET,
                RequestMethod::POST,
                RequestMethod::PUT,
                RequestMethod::PATCH,
                RequestMethod::DELETE,
            ]
        );

        $this->addRoute($route);
    }

    /**
     * Get all routes set by the application.
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return self::$collection->all();
    }

    /**
     * Get the route collection.
     *
     * @return RouteCollection
     */
    public function collection(): RouteCollection
    {
        return self::$collection;
    }

    /**
     * Get the route matcher.
     *
     * @return RouteMatcher
     */
    public function matcher(): RouteMatcher
    {
        return self::$collection->matcher();
    }

    /**
     * Get a route by name.
     *
     * @param string $name The name of the route to get
     *
     * @throws InvalidRouteName
     *
     * @return Route
     */
    public function route(string $name): Route
    {
        // If no route was found
        if (! $this->routeIsset($name)) {
            throw new InvalidRouteName($name);
        }

        return self::$collection->getNamed($name);
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
        return self::$collection->issetNamed($name);
    }

    /**
     * Get a route url by name.
     *
     * @param string $name     The name of the route to get
     * @param array  $data     [optional] The route data if dynamic
     * @param bool   $absolute [optional] Whether this url should be absolute
     *
     * @throws InvalidRouteName
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
        $host = $absolute || $route->isSecure() || $this->app->config(ConfigKey::ROUTING_USE_ABSOLUTE_URLS, false)
            ? $this->routeHost($route)
            : '';
        // Get the path from the generator
        $path = $route->getSegments()
            ? $this->app->pathGenerator()->parse(
                $route->getSegments(),
                $data,
                $route->getParams()
            )
            : $route->getPath();

        return $host . $this->validateRouteUrl($path);
    }

    /**
     * Get a route from a request.
     *
     * @param Request $request The request
     *
     * @throws InvalidArgumentException
     * @throws NotFoundHttpException
     *
     * @return Route
     */
    public function requestRoute(Request $request): Route
    {
        // Decode the request uri
        $requestUri = rawurldecode($request->getPathOnly());
        // Try to match the route
        $route = $this->matchRoute($requestUri, $request->getMethod());

        // If no route is found
        if (null === $route) {
            // Abort with 404
            $this->app->abort();
        }

        return $route;
    }

    /**
     * Get a route by path.
     *
     * @param string $path   The path
     * @param string $method [optional] The method type of get
     *
     * @throws InvalidArgumentException
     *
     * @return Route|null
     *      The route if found or null when no static route is
     *      found for the path and method combination specified
     */
    public function matchRoute(string $path, string $method = null): ?Route
    {
        return self::$collection->matcher()->match($path, $method);
    }

    /**
     * Determine if a uri is valid.
     *
     * @param string $uri The uri to check
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function isInternalUri(string $uri): bool
    {
        // Replace the scheme if it exists
        $uri = str_replace(['http://', 'https://'], '', $uri);

        // Get the host of the uri
        $host = (string) substr($uri, 0, strpos($uri, '/'));

        // If the host does not match the current request uri's host
        if ($host && $host !== $this->app->request()->getHttpHost()) {
            // Return false immediately
            return false;
        }

        // Get only the path (full string from the first slash to the end
        // of the path)
        $uri = (string) substr($uri, strpos($uri, '/'), count($uri));

        // Try to match the route
        $route = $this->matchRoute($uri);

        return $route instanceof Route;
    }

    /**
     * Dispatch the route and find a match.
     *
     * @param Request $request The request
     *
     * @throws NotFoundHttpException
     * @throws InvalidArgumentException
     *
     * @return Response
     */
    public function dispatch(Request $request): Response
    {
        // Get the route
        $route = $this->requestRoute($request);

        // Determine if the route is a redirect
        $this->determineRedirectRoute($route);
        // Determine if the route is secure and should be redirected
        $this->determineIsSecureRoute($request, $route);
        // Dispatch the route's before request handled middleware
        $this->routeRequestMiddleware($request, $route);

        // Trigger an event for route matched
        $this->app->events()->trigger(RouteMatched::class, [$route, $request]);
        // Set the found route in the service container
        $this->app->container()->singleton(Route::class, $route);

        // Attempt to dispatch the route using any one of the callable options
        $dispatch = $this->app->dispatcher()->dispatch($route, $route->getMatches());
        // Get the response from the dispatch
        $response = $this->getResponseFromDispatch($dispatch);

        // Dispatch the route's before request handled middleware and return the response
        $this->routeResponseMiddleware($request, $response, $route);

        return $response;
    }

    /**
     * Get a route's host.
     *
     * @param Route $route The route
     *
     * @return string
     */
    protected function routeHost(Route $route): string
    {
        return 'http'
            . ($route->isSecure() ? 's' : '')
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
        // If the last character is not a slash and the config is set to
        // ensure trailing slash
        if ($path[-1] !== '/' && $this->app->config(ConfigKey::ROUTING_TRAILING_SLASH, false)) {
            // add a trailing slash
            $path .= '/';
        }

        return $path;
    }

    /**
     * Determine if a route is a redirect.
     *
     * @param Route $route The route
     *
     * @return void
     */
    protected function determineRedirectRoute(Route $route): void
    {
        // If the route is a redirect and a redirect route is set
        if ($route->isRedirect() && $route->getRedirectPath()) {
            // Throw the redirect to the redirect path
            $this->app->redirect($route->getRedirectPath(), $route->getRedirectCode())->throw();
        }
    }

    /**
     * Determine if the route should be secure.
     *
     * @param Request $request The request
     * @param Route   $route   The route
     *
     * @return void
     */
    protected function determineIsSecureRoute(Request $request, Route $route): void
    {
        // If the route is secure and the current request is not secure
        if ($route->isSecure() && ! $request->isSecure()) {
            // Throw the redirect to the secure path
            $this->app->redirect()->secure($request->getPath())->throw();
        }
    }

    /**
     * Dispatch a route's before request handled middleware.
     *
     * @param Request $request The request
     * @param Route   $route   The route
     *
     * @return void
     */
    protected function routeRequestMiddleware(Request $request, Route $route): void
    {
        // If the route has no middleware
        if (null === $route->getMiddleware()) {
            return;
        }

        $middlewareReturn = $this->app->kernel()->requestMiddleware(
            $request,
            $route->getMiddleware()
        );
        // If the middleware returned a response
        if ($middlewareReturn instanceof Response) {
            // Return the response
            abortResponse($middlewareReturn);
        }
    }

    /**
     * Get a response from a dispatch.
     *
     * @param mixed $dispatch The dispatch
     *
     * @return Response
     */
    protected function getResponseFromDispatch($dispatch): Response
    {
        // If the dispatch failed, 404
        if (! $dispatch) {
            $this->app->abort();
        }

        // If the dispatch is a Response then simply return it
        if ($dispatch instanceof Response) {
            return $dispatch;
        }

        // If the dispatch is a View, render it then wrap it in a new response and return it
        if ($dispatch instanceof View) {
            return $this->app->response($dispatch->render());
        }

        // If the dispatch is an array, return it as JSON
        if (is_array($dispatch)) {
            return $this->app->json($dispatch);
        }

        // Otherwise its a string so wrap it in a new response and return it
        return $this->app->response((string) $dispatch);
    }

    /**
     * Dispatch a route's after request handled middleware.
     *
     * @param Request  $request  The request
     * @param Response $response The response
     * @param Route    $route    The route
     *
     * @return void
     */
    protected function routeResponseMiddleware(Request $request, Response $response, Route $route): void
    {
        // If the route has no middleware
        if (null === $route->getMiddleware()) {
            // Return the response passed through
            return;
        }

        $this->app->kernel()->responseMiddleware(
            $request,
            $response,
            $route->getMiddleware()
        );
    }
}
