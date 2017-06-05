<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Support\Middleware;

use Valkyrja\Application;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;

/**
 * Trait MiddlewareAwareTrait.
 *
 * @author Melech Mizrachi
 */
trait MiddlewareAwareTrait
{
    /**
     * The collection of middleware.
     *
     * @var string[]
     */
    protected static $middleware = [];

    /**
     * The collection of middleware groups.
     *
     * @var array[]
     */
    protected static $middlewareGroups = [];

    /**
     * Determine if a middleware is a group of middleware.
     *
     * @param string $middleware The middleware to check
     *
     * @return bool
     */
    public function isMiddlewareGroup(string $middleware): bool
    {
        return isset(self::$middlewareGroups[$middleware]);
    }

    /**
     * Get a middleware group.
     *
     * @param string $middleware The middleware group
     *
     * @return string[]
     */
    public function getMiddlewareGroup(string $middleware): array
    {
        return self::$middlewareGroups[$middleware];
    }

    /**
     * Dispatch middleware.
     *
     * @param \Valkyrja\Http\Request $request    The request
     * @param array                  $middleware [optional] The middleware to dispatch
     *
     * @return \Valkyrja\Http\Request
     */
    public function requestMiddleware(Request $request, array $middleware = null): Request
    {
        // Set the middleware to any middleware passed or the base middleware
        $middleware = $middleware ?? self::$middleware;

        // Get the application
        $app = $this->getApplication();

        // Iterate through the middleware
        foreach ($middleware as $item) {
            // If the middleware is a group
            if ($this->isMiddlewareGroup($item)) {
                // Recurse into that middleware group
                $this->requestMiddleware($request, $this->getMiddlewareGroup($item));

                continue;
            }

            /* @var \Valkyrja\Support\Middleware\Middleware $item */
            $request = $item::before($request);

            // Set the returned request in the container
            $app->container()->singleton(Request::class, $request);
        }

        return $request;
    }

    /**
     * Dispatch after request processed middleware.
     *
     * @param \Valkyrja\Http\Request  $request    The request
     * @param \Valkyrja\Http\Response $response   The response
     * @param array                   $middleware [optional] The middleware to dispatch
     *
     * @return \Valkyrja\Http\Response
     */
    public function responseMiddleware(Request $request, Response $response, array $middleware = null): Response
    {
        // Set the middleware to any middleware passed or the base middleware
        $middleware = $middleware ?? self::$middleware;

        // Get the application
        $app = $this->getApplication();

        // Iterate through the middleware
        foreach ($middleware as $item) {
            // If the middleware is a group
            if ($this->isMiddlewareGroup($item)) {
                // Recurse into that middleware group
                $this->responseMiddleware($request, $response, $this->getMiddlewareGroup($item));

                continue;
            }

            /* @var \Valkyrja\Support\Middleware\Middleware $item */
            $response = $item::after($request, $response);

            // Set the returned response in the container
            $app->container()->singleton(Response::class, $response);
        }

        return $response;
    }

    /**
     * Dispatch terminable middleware.
     *
     * @param \Valkyrja\Http\Request  $request    The request
     * @param \Valkyrja\Http\Response $response   The response
     * @param array                   $middleware [optional] The middleware to dispatch
     *
     * @return void
     */
    public function terminableMiddleware(Request $request, Response $response, array $middleware = null): void
    {
        // Set the middleware to any middleware passed or the base middleware
        $middleware = $middleware ?? self::$middleware;

        // Iterate through the middleware
        foreach ($middleware as $item) {
            // If the middleware is a group
            if ($this->isMiddlewareGroup($item)) {
                // Recurse into that middleware group
                $this->terminableMiddleware($request, $response, $this->getMiddlewareGroup($item));

                continue;
            }

            /* @var \Valkyrja\Support\Middleware\Middleware $item */
            $item::terminate($request, $response);
        }
    }

    /**
     * Get the application.
     *
     * @return \Valkyrja\Application
     */
    abstract protected function getApplication(): Application;
}