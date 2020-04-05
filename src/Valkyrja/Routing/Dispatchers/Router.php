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

namespace Valkyrja\Routing\Dispatchers;

use InvalidArgumentException;
use RuntimeException;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provides;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Event\Events;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\Kernel;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Path\PathGenerator;
use Valkyrja\Routing\Collection;
use Valkyrja\Routing\Collections\Collection as CollectionClass;
use Valkyrja\Routing\Events\RouteMatched;
use Valkyrja\Routing\Exceptions\InvalidRouteName;
use Valkyrja\Routing\Helpers\RouteGroup;
use Valkyrja\Routing\Helpers\RouteMethods;
use Valkyrja\Routing\Matcher;
use Valkyrja\Routing\Matchers\Matcher as MatcherClass;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Router as RouterContract;
use Valkyrja\View\View;

use function is_array;
use function rawurldecode;
use function str_replace;
use function strlen;
use function strpos;
use function substr;

/**
 * Class Router.
 *
 * @author Melech Mizrachi
 */
class Router implements RouterContract
{
    use Provides;
    use RouteGroup;
    use RouteMethods;

    /**
     * The route collection.
     *
     * @var Collection|null
     */
    protected static Collection $collection;

    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The dispatcher.
     *
     * @var Dispatcher
     */
    protected Dispatcher $dispatcher;

    /**
     * The events.
     *
     * @var Events
     */
    protected Events $events;

    /**
     * The path generator.
     *
     * @var PathGenerator
     */
    protected PathGenerator $pathGenerator;

    /**
     * The request.
     *
     * @var Request
     */
    protected Request $request;

    /**
     * The response factory.
     *
     * @var ResponseFactory
     */
    protected ResponseFactory $responseFactory;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * Router constructor.
     *
     * @param Container       $container
     * @param Dispatcher      $dispatcher
     * @param Events          $events
     * @param PathGenerator   $pathParser
     * @param Request         $request
     * @param ResponseFactory $responseFactory
     * @param Collection|null $collection
     * @param array           $config
     */
    public function __construct(
        Container $container,
        Dispatcher $dispatcher,
        Events $events,
        PathGenerator $pathParser,
        Request $request,
        ResponseFactory $responseFactory,
        Collection $collection,
        array $config
    ) {
        $this->container       = $container;
        $this->dispatcher      = $dispatcher;
        $this->events          = $events;
        $this->pathGenerator   = $pathParser;
        $this->request         = $request;
        $this->responseFactory = $responseFactory;
        $this->config          = $config;

        self::$collection = $collection;
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
     * @param Container $container The container
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            RouterContract::class,
            $router = new static(
                $container->getSingleton(Container::class),
                $container->getSingleton(Dispatcher::class),
                $container->getSingleton(Events::class),
                $container->getSingleton(PathGenerator::class),
                $container->getSingleton(Request::class),
                $container->getSingleton(ResponseFactory::class),
                new CollectionClass(new MatcherClass()),
                (array) $config['routing']
            )
        );

        $router->setup();
    }

    /**
     * Get the route collection.
     *
     * @return Collection
     */
    public function collection(): Collection
    {
        return self::$collection;
    }

    /**
     * Get the route matcher.
     *
     * @return Matcher
     */
    public function matcher(): Matcher
    {
        return self::$collection->matcher();
    }

    /**
     * Set a single route.
     *
     * @param Route $route The route
     *
     * @return void
     */
    public function addRoute(Route $route): void
    {
        // Set the route in the collection
        self::$collection->add($route);
    }

    /**
     * Get all routes set by the application.
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return self::$collection->allFlattened();
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
    public function getRoute(string $name): Route
    {
        // If no route was found
        if (! $this->hasRoute($name) || ! $route = self::$collection->getNamed($name)) {
            throw new InvalidRouteName($name);
        }

        return $route;
    }

    /**
     * Determine whether a route name exists.
     *
     * @param string $name The name of the route
     *
     * @return bool
     */
    public function hasRoute(string $name): bool
    {
        return self::$collection->hasNamed($name);
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
    public function getUrl(string $name, array $data = null, bool $absolute = null): string
    {
        // Get the matching route
        $route = $this->getRoute($name);
        // Set the host to use if this is an absolute url
        // or the config is set to always use absolute urls
        // or the route is secure (needs https:// appended)
        $host = $absolute || $route->isSecure() || $this->config['useAbsoluteUrls']
            ? $this->routeHost($route)
            : '';
        // Get the path from the generator
        $path = $route->getSegments()
            ? $this->pathGenerator->parse(
                $route->getSegments(),
                $data,
                $route->getParams()
            )
            : $route->getPath();

        if (null === $path) {
            throw new RuntimeException('Invalid path for route with name: ' . $name);
        }

        return $host . $this->validateRouteUrl($path);
    }

    /**
     * Get a route from a request.
     *
     * @param Request $request The request
     *
     * @throws InvalidArgumentException
     * @throws HttpException
     *
     * @return Route
     */
    public function getRouteFromRequest(Request $request): Route
    {
        // Decode the request uri
        $requestUri = rawurldecode($request->getUri()->getPath());
        // Try to match the route
        $route = $this->getRouteByPath($requestUri, $request->getMethod());

        // If no route is found
        if (null === $route) {
            // Abort with 404
            throw new $this->config['httpException']();
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
    public function getRouteByPath(string $path, string $method = null): ?Route
    {
        return self::$collection->matcher()->match($path, $method);
    }

    /**
     * Determine if a uri is internal.
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
        if ($host && $host !== $this->request->getUri()->getHost()) {
            // Return false immediately
            return false;
        }

        // Get only the path (full string from the first slash to the end of the path)
        $uri = (string) substr($uri, strpos($uri, '/'), strlen($uri));

        // Try to match the route
        $route = $this->getRouteByPath($uri);

        return $route instanceof Route;
    }

    /**
     * Set the data from cache.
     *
     * @param bool $force    [optional] Whether to force setup
     * @param bool $useCache [optional] Whether to use cache
     *
     * @return void
     */
    public function setup(bool $force = false, bool $useCache = true): void
    {
        self::$collection->setup($force, $useCache);
    }

    /**
     * Get a cacheable representation of the data.
     *
     * @return object
     */
    public function getCacheable(): object
    {
        return self::$collection->getCacheable();
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
            . \Valkyrja\request()->getUri()->getHostPort();
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
        if ($path[-1] !== '/' && $this->config['useTrailingSlash']) {
            // add a trailing slash
            $path .= '/';
        }

        return $path;
    }

    /**
     * Dispatch the route and find a match.
     *
     * @param Request $request The request
     *
     * @throws InvalidArgumentException
     *
     * @return Response
     */
    public function dispatch(Request $request): Response
    {
        // Get the route
        $route = $this->getRouteFromRequest($request);

        // Determine if the route is a redirect
        $this->determineRedirectRoute($route);
        // Determine if the route is secure and should be redirected
        $this->determineIsSecureRoute($request, $route);
        // Dispatch the route's before request handled middleware
        $request = $this->routeRequestMiddleware($request, $route);

        // Trigger an event for route matched
        $this->events->trigger(RouteMatched::class, [$route, $request]);
        // Set the found route in the service container
        $this->container->setSingleton(Route::class, $route);

        // Attempt to dispatch the route using any one of the callable options
        $dispatch = $this->dispatcher->dispatch($route, $route->getMatches());
        // Get the response from the dispatch
        $response = $this->getResponseFromDispatch($dispatch);

        // Dispatch the route's before request handled middleware and return the response
        return $this->routeResponseMiddleware($request, $response, $route);
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
        if ($route->isRedirect()) {
            // Throw the redirect to the redirect path
            $this->responseFactory->createRedirectResponse($route->getTo(), $route->getCode())->throw();
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
        if ($route->isSecure() && ! $request->getUri()->isSecure()) {
            // Throw the redirect to the secure path
            $this->responseFactory->createRedirectResponse()->secure($request->getUri()->getPath())->throw();
        }
    }

    /**
     * Dispatch a route's before request handled middleware.
     *
     * @param Request $request The request
     * @param Route   $route   The route
     *
     * @return Request
     */
    protected function routeRequestMiddleware(Request $request, Route $route): Request
    {
        return $this->getKernel()->requestMiddleware($request, $route->getMiddleware() ?? []);
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
        // If the dispatch is a Response then simply return it
        if ($dispatch instanceof Response) {
            return $dispatch;
        }

        // If the dispatch is a View, render it then wrap it in a new response and return it
        if ($dispatch instanceof View) {
            return $this->responseFactory->createResponse($dispatch->render());
        }

        // If the dispatch is an array, return it as JSON
        if (is_array($dispatch)) {
            return $this->responseFactory->createJsonResponse($dispatch);
        }

        // Otherwise its a string so wrap it in a new response and return it
        return $this->responseFactory->createResponse((string) $dispatch);
    }

    /**
     * Dispatch a route's after request handled middleware.
     *
     * @param Request  $request  The request
     * @param Response $response The response
     * @param Route    $route    The route
     *
     * @return Response
     */
    protected function routeResponseMiddleware(Request $request, Response $response, Route $route): Response
    {
        return $this->getKernel()->responseMiddleware(
            $request,
            $response,
            $route->getMiddleware() ?? []
        );
    }

    /**
     * Get the kernel.
     *
     * @return Kernel
     */
    protected function getKernel(): Kernel
    {
        return $this->container->getSingleton(Kernel::class);
    }
}
