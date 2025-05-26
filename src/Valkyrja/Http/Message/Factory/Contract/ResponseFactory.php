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

namespace Valkyrja\Http\Message\Factory\Contract;

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Response\Contract\JsonResponse;
use Valkyrja\Http\Message\Response\Contract\RedirectResponse;
use Valkyrja\Http\Message\Response\Contract\Response;

/**
 * Interface ResponseFactory.
 *
 * @author Melech Mizrachi
 */
interface ResponseFactory
{
    /**
     * Create a response.
     *
     * @param string|null                  $content    [optional] The response content
     * @param StatusCode|null              $statusCode [optional] The response status code
     * @param array<string, string[]>|null $headers    [optional] An array of response headers
     *
     * @return Response
     */
    public function createResponse(
        string|null $content = null,
        StatusCode|null $statusCode = null,
        array|null $headers = null
    ): Response;

    /**
     * Create a JSON response.
     *
     * @param array<array-key, mixed>|null $data       [optional] The data to set
     * @param StatusCode|null              $statusCode [optional] The response status code
     * @param array<string, string[]>|null $headers    [optional] An array of response headers
     *
     * @return JsonResponse
     */
    public function createJsonResponse(
        array|null $data = null,
        StatusCode|null $statusCode = null,
        array|null $headers = null
    ): JsonResponse;

    /**
     * Create a JSONP response.
     *
     * @param string                       $callback   The jsonp callback
     * @param array<array-key, mixed>|null $data       [optional] The data to set
     * @param StatusCode|null              $statusCode [optional] The response status code
     * @param array<string, string[]>|null $headers    [optional] An array of response headers
     *
     * @return JsonResponse
     */
    public function createJsonpResponse(
        string $callback,
        array|null $data = null,
        StatusCode|null $statusCode = null,
        array|null $headers = null
    ): JsonResponse;

    /**
     * Create a redirect response.
     *
     * @param string|null                  $uri        [optional] The uri to redirect to
     * @param StatusCode|null              $statusCode [optional] The response status code
     * @param array<string, string[]>|null $headers    [optional] An array of response headers
     *
     * @return RedirectResponse
     */
    public function createRedirectResponse(
        string|null $uri = null,
        StatusCode|null $statusCode = null,
        array|null $headers = null
    ): RedirectResponse;
}
