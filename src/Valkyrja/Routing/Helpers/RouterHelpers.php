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

use InvalidArgumentException;
use RuntimeException;
use Valkyrja\Application\Application;
use Valkyrja\Config\Enums\ConfigKey;
use Valkyrja\Dispatcher\Exceptions\InvalidClosureException;
use Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\Request;
use Valkyrja\Routing\Exceptions\InvalidRouteName;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Collection;
use Valkyrja\Routing\Matcher;

use function strlen;

/**
 * Trait RouterHelpers.
 *
 * @author Melech Mizrachi
 *
 * @property Collection  $collection
 * @property Application $app
 */
trait RouterHelpers
{
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
        if ($host && $host !== $this->app->request()->getUri()->getHost()) {
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
     * Get the route collection.
     *
     * @return Collection
     */
    abstract public function collection(): Collection;

    /**
     * Get the route matcher.
     *
     * @return Matcher
     */
    abstract public function matcher(): Matcher;

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
            . request()->getUri()->getHostPort();
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
}
