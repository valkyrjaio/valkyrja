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

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Enum\StatusText;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Throwable\Exception\HttpException;
use Valkyrja\Http\Message\Throwable\Exception\HttpRedirectException;
use Valkyrja\Http\Message\Uri\Contract\UriContract;

class Abort
{
    /**
     * Abort with a 400.
     */
    public static function abort400(HeaderCollectionContract|null $headers = null, ResponseContract|null $response = null): never
    {
        static::abort(StatusCode::BAD_REQUEST, StatusText::BAD_REQUEST->value, $headers, $response);
    }

    /**
     * Abort with a 404.
     */
    public static function abort404(HeaderCollectionContract|null $headers = null, ResponseContract|null $response = null): never
    {
        static::abort(StatusCode::NOT_FOUND, StatusText::NOT_FOUND->value, $headers, $response);
    }

    /**
     * Abort with a 405.
     */
    public static function abort405(HeaderCollectionContract|null $headers = null, ResponseContract|null $response = null): never
    {
        static::abort(StatusCode::METHOD_NOT_ALLOWED, StatusText::METHOD_NOT_ALLOWED->value, $headers, $response);
    }

    /**
     * Abort with a 413.
     */
    public static function abort413(HeaderCollectionContract|null $headers = null, ResponseContract|null $response = null): never
    {
        static::abort(StatusCode::PAYLOAD_TOO_LARGE, StatusText::PAYLOAD_TOO_LARGE->value, $headers, $response);
    }

    /**
     * Abort.
     *
     * @param string|null $message [optional] The message
     */
    public static function abort(
        StatusCode|null $statusCode = null,
        string|null $message = null,
        HeaderCollectionContract|null $headers = null,
        ResponseContract|null $response = null
    ): never {
        throw new HttpException($statusCode, $message, $headers, $response);
    }

    /**
     * Redirect to a given uri, and abort.
     *
     * @throws HttpRedirectException
     */
    public static function redirect(
        UriContract|null $uri = null,
        StatusCode|null $statusCode = null,
        HeaderCollectionContract|null $headers = null
    ): never {
        throw new HttpRedirectException($uri, $statusCode, $headers);
    }
}
