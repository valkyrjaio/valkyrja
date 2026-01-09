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

namespace Valkyrja\Tests\Unit\Http\Routing\Support;

use Valkyrja\Http\Message\Constant\StatusText;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Message\Throwable\Exception\HttpException;
use Valkyrja\Http\Message\Throwable\Exception\HttpRedirectException;
use Valkyrja\Http\Message\Uri\Uri;
use Valkyrja\Http\Routing\Support\Abort;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Class AbortTest.
 */
class AbortTest extends TestCase
{
    public function testAbort(): void
    {
        $this->expectException(HttpException::class);

        Abort::abort();
    }

    public function testAbortWithArguments(): void
    {
        $this->expectException(HttpException::class);

        $statusCode      = StatusCode::BAD_GATEWAY;
        $message         = 'message';
        $responseMessage = 'response message';
        $headers         = ['Header' => ['test']];
        $response        = Response::create($responseMessage);

        try {
            Abort::abort(
                statusCode: $statusCode,
                message: $message,
                headers: $headers,
                response: $response
            );
        } catch (HttpException $exception) {
            self::assertSame($statusCode, $exception->getStatusCode());
            self::assertSame($message, $exception->getMessage());
            self::assertSame($headers, $exception->getHeaders());
            self::assertNotNull($exceptionResponse = $exception->getResponse());
            self::assertSame($response->getBody()->__toString(), $exceptionResponse->getBody()->__toString());

            throw $exception;
        }
    }

    public function testRedirect(): void
    {
        $this->expectException(HttpRedirectException::class);

        Abort::redirect();
    }

    public function testRedirectWithArguments(): void
    {
        $this->expectException(HttpRedirectException::class);

        $statusCode = StatusCode::PERMANENT_REDIRECT;
        $url        = 'https://example.com/';
        $headers    = ['Header' => ['test']];
        $uri        = Uri::fromString($url);

        try {
            Abort::redirect(
                uri: $uri,
                statusCode: $statusCode,
                headers: $headers,
            );
        } catch (HttpRedirectException $exception) {
            self::assertSame($statusCode, $exception->getStatusCode());
            self::assertSame($uri, $exception->getUri());
            self::assertSame($headers, $exception->getHeaders());

            throw $exception;
        }
    }

    public function testAbort400(): void
    {
        $this->expectException(HttpException::class);

        $responseMessage = 'response message';
        $headers         = ['Header' => ['test']];
        $response        = Response::create($responseMessage);

        try {
            Abort::abort400(
                headers: $headers,
                response: $response,
            );
        } catch (HttpException $exception) {
            self::assertSame(StatusCode::BAD_REQUEST, $exception->getStatusCode());
            self::assertSame(StatusText::BAD_REQUEST, $exception->getMessage());
            self::assertSame($headers, $exception->getHeaders());
            self::assertNotNull($exceptionResponse = $exception->getResponse());
            self::assertSame($response->getBody()->__toString(), $exceptionResponse->getBody()->__toString());

            throw $exception;
        }
    }

    public function testAbort404(): void
    {
        $this->expectException(HttpException::class);

        $responseMessage = 'response message';
        $headers         = ['Header' => ['test']];
        $response        = Response::create($responseMessage);

        try {
            Abort::abort404(
                headers: $headers,
                response: $response,
            );
        } catch (HttpException $exception) {
            self::assertSame(StatusCode::NOT_FOUND, $exception->getStatusCode());
            self::assertSame(StatusText::NOT_FOUND, $exception->getMessage());
            self::assertSame($headers, $exception->getHeaders());
            self::assertNotNull($exceptionResponse = $exception->getResponse());
            self::assertSame($response->getBody()->__toString(), $exceptionResponse->getBody()->__toString());

            throw $exception;
        }
    }

    public function testAbort405(): void
    {
        $this->expectException(HttpException::class);

        $responseMessage = 'response message';
        $headers         = ['Header' => ['test']];
        $response        = Response::create($responseMessage);

        try {
            Abort::abort405(
                headers: $headers,
                response: $response,
            );
        } catch (HttpException $exception) {
            self::assertSame(StatusCode::METHOD_NOT_ALLOWED, $exception->getStatusCode());
            self::assertSame(StatusText::METHOD_NOT_ALLOWED, $exception->getMessage());
            self::assertSame($headers, $exception->getHeaders());
            self::assertNotNull($exceptionResponse = $exception->getResponse());
            self::assertSame($response->getBody()->__toString(), $exceptionResponse->getBody()->__toString());

            throw $exception;
        }
    }

    public function testAbort413(): void
    {
        $this->expectException(HttpException::class);

        $responseMessage = 'response message';
        $headers         = ['Header' => ['test']];
        $response        = Response::create($responseMessage);

        try {
            Abort::abort413(
                headers: $headers,
                response: $response,
            );
        } catch (HttpException $exception) {
            self::assertSame(StatusCode::PAYLOAD_TOO_LARGE, $exception->getStatusCode());
            self::assertSame(StatusText::PAYLOAD_TOO_LARGE, $exception->getMessage());
            self::assertSame($headers, $exception->getHeaders());
            self::assertNotNull($exceptionResponse = $exception->getResponse());
            self::assertSame($response->getBody()->__toString(), $exceptionResponse->getBody()->__toString());

            throw $exception;
        }
    }
}
