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

use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Http\Exceptions\HttpRedirectException;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Router;

if (! function_exists('request')) {
    /**
     * Get request.
     *
     * @return Request
     */
    function request(): Request
    {
        return app()->request();
    }
}

if (! function_exists('router')) {
    /**
     * Get router.
     *
     * @return Router
     */
    function router(): Router
    {
        return app()->router();
    }
}

if (! function_exists('route')) {
    /**
     * Get a route by name.
     *
     * @param string $name The name of the route to get
     *
     * @return Route
     */
    function route(string $name): Route
    {
        return router()->getRoute($name);
    }
}

if (! function_exists('routeUrl')) {
    /**
     * Get a route url by name.
     *
     * @param string $name     The name of the route to get
     * @param array  $data     [optional] The route data if dynamic
     * @param bool   $absolute [optional] Whether this url should be absolute
     *
     * @return string
     */
    function routeUrl(string $name, array $data = null, bool $absolute = null): string
    {
        return router()->getUrl($name, $data, $absolute);
    }
}

if (! function_exists('responseBuilder')) {
    /**
     * Get the response builder.
     *
     * @return ResponseFactory
     */
    function responseBuilder(): ResponseFactory
    {
        return app()->responseFactory();
    }
}

if (! function_exists('response')) {
    /**
     * Return a new response from the application.
     *
     * @param string|null $content    [optional] The content to set
     * @param int|null    $statusCode [optional] The status code to set
     * @param array|null  $headers    [optional] The headers to set
     *
     * @return Response
     */
    function response(string $content = null, int $statusCode = null, array $headers = null): Response
    {
        return app()->response($content, $statusCode, $headers);
    }
}

if (! function_exists('json')) {
    /**
     * Return a new json response from the application.
     *
     * @param array|null $data       [optional] An array of data
     * @param int|null   $statusCode [optional] The status code to set
     * @param array|null $headers    [optional] The headers to set
     *
     * @return JsonResponse
     */
    function json(array $data = null, int $statusCode = null, array $headers = null): JsonResponse
    {
        return app()->json($data, $statusCode, $headers);
    }
}

if (! function_exists('redirect')) {
    /**
     * Return a new redirect response from the application.
     *
     * @param string|null $uri        [optional] The URI to redirect to
     * @param int|null    $statusCode [optional] The response status code
     * @param array|null  $headers    [optional] An array of response headers
     *
     * @return RedirectResponse
     */
    function redirect(string $uri = null, int $statusCode = null, array $headers = null): RedirectResponse
    {
        return app()->redirect($uri, $statusCode, $headers);
    }
}

if (! function_exists('redirectRoute')) {
    /**
     * Return a new redirect response from the application for a given route.
     *
     * @param string     $route      The route to match
     * @param array|null $parameters [optional] Any parameters to set for dynamic routes
     * @param int|null   $statusCode [optional] The response status code
     * @param array|null $headers    [optional] An array of response headers
     *
     * @return RedirectResponse
     */
    function redirectRoute(
        string $route,
        array $parameters = null,
        int $statusCode = null,
        array $headers = null
    ): RedirectResponse {
        return app()->redirectRoute($route, $parameters, $statusCode, $headers);
    }
}

if (! function_exists('redirectTo')) {
    /**
     * Redirect to a given uri, and abort the application.
     *
     * @param string $uri        [optional] The URI to redirect to
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @throws HttpRedirectException
     *
     * @return void
     */
    function redirectTo(
        string $uri = null,
        int $statusCode = StatusCode::FOUND,
        array $headers = []
    ): void {
        app()->redirectTo($uri, $statusCode, $headers);
    }
}
