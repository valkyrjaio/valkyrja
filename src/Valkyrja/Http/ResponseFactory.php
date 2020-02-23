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

use Valkyrja\Http\Enums\StatusCode;

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
     * @param mixed $content    [optional] The response content
     * @param int   $statusCode [optional] The response status code
     * @param array $headers    [optional] An array of response headers
     *
     * @return Response
     */
    public function make(string $content = '', int $statusCode = StatusCode::OK, array $headers = []): Response;

    /**
     * View response builder.
     *
     * @param string $template   The view template to use
     * @param array  $data       [optional] The view data
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @return Response
     */
    public function view(
        string $template,
        array $data = [],
        int $statusCode = StatusCode::OK,
        array $headers = []
    ): Response;

    /**
     * Json response builder.
     *
     * @param array $data       [optional] The data to set
     * @param int   $statusCode [optional] The response status code
     * @param array $headers    [optional] An array of response headers
     *
     * @return JsonResponse
     */
    public function json(array $data = [], int $statusCode = StatusCode::OK, array $headers = []): JsonResponse;

    /**
     * JsonP response builder.
     *
     * @param string $callback   The jsonp callback
     * @param array  $data       [optional] The data to set
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @return JsonResponse
     */
    public function jsonp(
        string $callback,
        array $data = [],
        int $statusCode = StatusCode::OK,
        array $headers = []
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
    public function redirect(
        string $uri = '/',
        int $statusCode = StatusCode::FOUND,
        array $headers = []
    ): RedirectResponse;

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
        array $parameters = [],
        int $statusCode = StatusCode::FOUND,
        array $headers = []
    ): RedirectResponse;
}
