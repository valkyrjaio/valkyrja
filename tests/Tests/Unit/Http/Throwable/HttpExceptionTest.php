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

namespace Valkyrja\Tests\Unit\Http\Throwable;

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Message\Throwable\Exception\HttpException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the HttpException class.
 */
final class HttpExceptionTest extends TestCase
{
    public function testConstruct(): void
    {
        $exception = new HttpException();

        self::assertSame(StatusCode::INTERNAL_SERVER_ERROR, $exception->getStatusCode());
        self::assertEmpty($exception->getHeaders());
        self::assertNull($exception->getResponse());
    }

    public function testGetStatusCode(): void
    {
        $exception = new HttpException(statusCode: StatusCode::SERVICE_UNAVAILABLE);

        self::assertSame(StatusCode::SERVICE_UNAVAILABLE, $exception->getStatusCode());
    }

    public function testGetHeaders(): void
    {
        $exception = new HttpException(headers: $headers = ['test' => ['foo', 'bar']]);

        self::assertSame($headers, $exception->getHeaders());
    }

    public function testGetMessage(): void
    {
        $exception = new HttpException(message: $message = 'test');

        self::assertSame($message, $exception->getMessage());
    }

    public function testGetResponse(): void
    {
        $response  = new Response(statusCode: StatusCode::INTERNAL_SERVER_ERROR);
        $exception = new HttpException(response: $response);

        self::assertNotSame($response, $exception->getResponse());
        self::assertSame($response->getStatusCode(), $exception->getResponse()?->getStatusCode());
    }

    public function testGetResponseWithNoStatusCode(): void
    {
        $response  = new Response();
        $exception = new HttpException(response: $response);

        self::assertNotSame($response, $exception->getResponse());
        self::assertSame($response->getStatusCode(), $exception->getResponse()?->getStatusCode());
    }
}
