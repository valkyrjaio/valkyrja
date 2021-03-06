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

namespace Valkyrja\Routing\Urls;

use InvalidArgumentException;
use RuntimeException;
use Valkyrja\Http\Constants\RequestMethod;
use Valkyrja\Http\Request;
use Valkyrja\Path\PathGenerator;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Router;
use Valkyrja\Routing\Url as Contract;

use function str_replace;
use function strlen;
use function strpos;
use function substr;

/**
 * Class Url.
 *
 * @author Melech Mizrachi
 */
class Url implements Contract
{
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
     * The router.
     *
     * @var Router
     */
    protected Router $router;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * Router constructor.
     *
     * @param PathGenerator $pathGenerator The path generator
     * @param Request       $request       The request
     * @param Router        $router        The router
     * @param array         $config        The routing config
     */
    public function __construct(PathGenerator $pathGenerator, Request $request, Router $router, array $config)
    {
        $this->pathGenerator = $pathGenerator;
        $this->request       = $request;
        $this->router        = $router;
        $this->config        = $config;
    }

    /**
     * Get a route url by name.
     *
     * @param string     $name     The name of the route to get
     * @param array|null $data     [optional] The route data if dynamic
     * @param bool       $absolute [optional] Whether this url should be absolute
     *
     * @return string
     */
    public function getUrl(string $name, array $data = null, bool $absolute = null): string
    {
        // Get the matching route
        $route = $this->router->getRoute($name);
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
     * Get a route by path.
     *
     * @param string      $path   The path
     * @param string|null $method [optional] The method type of get
     *
     * @return Route|null
     *      The route if found or null when no static route is
     *      found for the path and method combination specified
     */
    public function getRouteByPath(string $path, string $method = null): ?Route
    {
        return $this->router->getMatcher()->match($path, $method ?? RequestMethod::GET);
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
            . $this->request->getUri()->getHostPort();
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
}
