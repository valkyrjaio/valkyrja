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

namespace Valkyrja\Http;

/**
 * Interface ResponseFactory.
 *
 * @author Melech Mizrachi
 */
interface ResponseFactory
{
    /**
     * Make a new instance of Response.
     *
     * @param string|null $content    [optional] The response content
     * @param int|null    $statusCode [optional] The response status code
     * @param array|null  $headers    [optional] An array of response headers
     *
     * @return Response
     */
    public function make(string $content = null, int $statusCode = null, array $headers = null): Response;

    /**
     * Json response builder.
     *
     * @param array|null $data       [optional] The data to set
     * @param int|null   $statusCode [optional] The response status code
     * @param array|null $headers    [optional] An array of response headers
     *
     * @return JsonResponse
     */
    public function json(array $data = null, int $statusCode = null, array $headers = null): JsonResponse;

    /**
     * JsonP response builder.
     *
     * @param string     $callback   The jsonp callback
     * @param array|null $data       [optional] The data to set
     * @param int|null   $statusCode [optional] The response status code
     * @param array|null $headers    [optional] An array of response headers
     *
     * @return JsonResponse
     */
    public function jsonp(
        string $callback,
        array $data = null,
        int $statusCode = null,
        array $headers = null
    ): JsonResponse;

    /**
     * Redirect to response builder.
     *
     * @param string $uri        [optional] The uri to redirect to
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @return RedirectResponse
     */
    public function redirect(string $uri = null, int $statusCode = null, array $headers = null): RedirectResponse;

    /**
     * Redirect to a named route response builder.
     *
     * @param string $route      The route to match
     * @param array  $parameters [optional] Any parameters to set for dynamic
     *                           routes
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @return RedirectResponse
     */
    public function route(
        string $route,
        array $parameters = null,
        int $statusCode = null,
        array $headers = null
    ): RedirectResponse;

    /**
     * View response builder.
     *
     * @param string     $template   The view template to use
     * @param array|null $data       [optional] The view data
     * @param int|null   $statusCode [optional] The response status code
     * @param array|null $headers    [optional] An array of response headers
     *
     * @return Response
     */
    public function view(string $template, array $data = null, int $statusCode = null, array $headers = null): Response;
}
