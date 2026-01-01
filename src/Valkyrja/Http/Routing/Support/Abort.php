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

namespace Valkyrja\Http\Routing\Support;

use Valkyrja\Http\Message\Constant\StatusText;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Throwable\Exception\HttpException;
use Valkyrja\Http\Message\Throwable\Exception\HttpRedirectException;
use Valkyrja\Http\Message\Uri\Contract\UriContract;

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
     * @param array<string, string[]>|null $headers  [optional] The headers
     * @param ResponseContract|null        $response [optional] The response to send
     *
     * @return never
     */
    public static function abort400(array|null $headers = null, ResponseContract|null $response = null): never
    {
        static::abort(StatusCode::BAD_REQUEST, StatusText::BAD_REQUEST, $headers, $response);
    }

    /**
     * Abort with a 404.
     *
     * @param array<string, string[]>|null $headers  [optional] The headers
     * @param ResponseContract|null        $response [optional] The response to send
     *
     * @return never
     */
    public static function abort404(array|null $headers = null, ResponseContract|null $response = null): never
    {
        static::abort(StatusCode::NOT_FOUND, StatusText::NOT_FOUND, $headers, $response);
    }

    /**
     * Abort with a 405.
     *
     * @param array<string, string[]>|null $headers  [optional] The headers
     * @param ResponseContract|null        $response [optional] The response to send
     *
     * @return never
     */
    public static function abort405(array|null $headers = null, ResponseContract|null $response = null): never
    {
        static::abort(StatusCode::METHOD_NOT_ALLOWED, StatusText::METHOD_NOT_ALLOWED, $headers, $response);
    }

    /**
     * Abort with a 413.
     *
     * @param array<string, string[]>|null $headers  [optional] The headers
     * @param ResponseContract|null        $response [optional] The response to send
     *
     * @return never
     */
    public static function abort413(array|null $headers = null, ResponseContract|null $response = null): never
    {
        static::abort(StatusCode::PAYLOAD_TOO_LARGE, StatusText::PAYLOAD_TOO_LARGE, $headers, $response);
    }

    /**
     * Abort.
     *
     * @param StatusCode|null              $statusCode [optional] The status code
     * @param string|null                  $message    [optional] The message
     * @param array<string, string[]>|null $headers    [optional] The headers
     * @param ResponseContract|null        $response   [optional] The response to send
     *
     * @return never
     */
    public static function abort(
        StatusCode|null $statusCode = null,
        string|null $message = null,
        array|null $headers = null,
        ResponseContract|null $response = null
    ): never {
        throw new HttpException($statusCode, $message, $headers, $response);
    }

    /**
     * Redirect to a given uri, and abort.
     *
     * @param UriContract|null             $uri        [optional] The URI to redirect to
     * @param StatusCode|null              $statusCode [optional] The response status code
     * @param array<string, string[]>|null $headers    [optional] An array of response headers
     *
     * @throws HttpRedirectException
     *
     * @return never
     */
    public static function redirect(
        UriContract|null $uri = null,
        StatusCode|null $statusCode = null,
        array|null $headers = null
    ): never {
        throw new HttpRedirectException($uri, $statusCode, $headers);
    }
}
