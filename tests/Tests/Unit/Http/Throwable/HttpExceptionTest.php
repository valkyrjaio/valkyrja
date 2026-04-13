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
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Message\Throwable\Exception\HttpResponseException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the HttpException class.
 */
final class HttpExceptionTest extends TestCase
{
    public function testConstruct(): void
    {
        $exception = new HttpResponseException();

        self::assertSame(StatusCode::INTERNAL_SERVER_ERROR, $exception->getStatusCode());
        self::assertEmpty($exception->getHeaders()->getAll());
        self::assertNull($exception->getResponse());
    }

    public function testGetStatusCode(): void
    {
        $exception = new HttpResponseException(statusCode: StatusCode::SERVICE_UNAVAILABLE);

        self::assertSame(StatusCode::SERVICE_UNAVAILABLE, $exception->getStatusCode());
    }

    public function testGetHeaders(): void
    {
        $headers   = ['test' => new Header('test', ...['foo', 'bar'])];
        $exception = new HttpResponseException(headers: HeaderCollection::fromArray($headers));

        self::assertSame($headers, $exception->getHeaders()->getAll());
    }

    public function testGetMessage(): void
    {
        $exception = new HttpResponseException(message: $message = 'test');

        self::assertSame($message, $exception->getMessage());
    }

    public function testGetResponse(): void
    {
        $response  = new Response(statusCode: StatusCode::INTERNAL_SERVER_ERROR);
        $exception = new HttpResponseException(response: $response);

        self::assertNotSame($response, $exception->getResponse());
        self::assertSame($response->getStatusCode(), $exception->getResponse()?->getStatusCode());
    }

    public function testGetResponseWithNoStatusCode(): void
    {
        $response  = new Response();
        $exception = new HttpResponseException(response: $response);

        self::assertNotSame($response, $exception->getResponse());
        self::assertSame($response->getStatusCode(), $exception->getResponse()?->getStatusCode());
    }
}
