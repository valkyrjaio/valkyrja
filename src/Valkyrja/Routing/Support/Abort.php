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

namespace Valkyrja\Routing\Support;

use Valkyrja\Http\Constant\StatusCode;
use Valkyrja\Http\Constant\StatusText;
use Valkyrja\Http\Exception\HttpException;
use Valkyrja\Http\Exception\HttpRedirectException;
use Valkyrja\Http\Response\Contract\Response;

/**
 * Class Abort.
 *
 * @author Melech Mizrachi
 */
class Abort
{
    /**
     * Abort with a 400.
     *
     * @param array|null    $headers  [optional] The headers
     * @param Response|null $response [optional] The response to send
     *
     * @return never
     */
    public static function abort400(array|null $headers = null, Response|null $response = null): never
    {
        static::abort(StatusCode::BAD_REQUEST, StatusText::BAD_REQUEST, $headers, $response);
    }

    /**
     * Abort with a 404.
     *
     * @param array|null    $headers  [optional] The headers
     * @param Response|null $response [optional] The response to send
     *
     * @return never
     */
    public static function abort404(array|null $headers = null, Response|null $response = null): never
    {
        static::abort(StatusCode::NOT_FOUND, StatusText::NOT_FOUND, $headers, $response);
    }

    /**
     * Abort with a 405.
     *
     * @param array|null    $headers  [optional] The headers
     * @param Response|null $response [optional] The response to send
     *
     * @return never
     */
    public static function abort405(array|null $headers = null, Response|null $response = null): never
    {
        static::abort(StatusCode::METHOD_NOT_ALLOWED, StatusText::METHOD_NOT_ALLOWED, $headers, $response);
    }

    /**
     * Abort with a 413.
     *
     * @param array|null    $headers  [optional] The headers
     * @param Response|null $response [optional] The response to send
     *
     * @return never
     */
    public static function abort413(array|null $headers = null, Response|null $response = null): never
    {
        static::abort(StatusCode::PAYLOAD_TOO_LARGE, StatusText::PAYLOAD_TOO_LARGE, $headers, $response);
    }

    /**
     * Abort.
     *
     * @param int|null      $statusCode [optional] The status code
     * @param string|null   $message    [optional] The message
     * @param array|null    $headers    [optional] The headers
     * @param Response|null $response   [optional] The response to send
     *
     * @return never
     */
    public static function abort(
        int|null $statusCode = null,
        string|null $message = null,
        array|null $headers = null,
        Response|null $response = null
    ): never {
        throw new HttpException($statusCode, $message, $headers, $response);
    }

    /**
     * Abort with a response.
     *
     * @param Response $response The response
     *
     * @return never
     */
    public static function response(Response $response): never
    {
        static::abort(null, null, null, $response);
    }

    /**
     * Redirect to a given uri, and abort.
     *
     * @param string|null $uri        [optional] The URI to redirect to
     * @param int|null    $statusCode [optional] The response status code
     * @param array|null  $headers    [optional] An array of response headers
     *
     * @throws HttpRedirectException
     *
     * @return never
     */
    public static function redirect(string|null $uri = null, int|null $statusCode = null, array|null $headers = null): never
    {
        throw new HttpRedirectException($statusCode, $uri, $headers);
    }
}
