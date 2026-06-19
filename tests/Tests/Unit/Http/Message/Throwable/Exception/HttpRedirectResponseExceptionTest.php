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

namespace Valkyrja\Tests\Unit\Http\Message\Throwable\Exception;

use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Throwable\Exception\HttpRedirectResponseException;
use Valkyrja\Http\Message\Throwable\Exception\HttpResponseException;
use Valkyrja\Http\Message\Uri\Contract\UriContract;
use Valkyrja\Http\Message\Uri\Uri;

use Valkyrja\Tests\Unit\Abstract\TestCase;

final class HttpRedirectResponseExceptionTest extends TestCase
{
    public function testExtendsHttpResponseException(): void
    {
        self::assertInstanceOf(HttpResponseException::class, new HttpRedirectResponseException());
    }

    public function testDefaultsToFoundStatusCodeAndRootUriAndGeneratedResponse(): void
    {
        $exception = new HttpRedirectResponseException();

        self::assertSame(StatusCode::FOUND, $exception->getStatusCode());
        self::assertInstanceOf(UriContract::class, $exception->getUri());
        self::assertSame('/', $exception->getUri()->getPath());
        self::assertInstanceOf(ResponseContract::class, $exception->getResponse());
        self::assertSame('Redirect', $exception->getMessage());
    }

    public function testUsesProvidedUriStatusCodeHeadersAndResponse(): void
    {
        $uri      = new Uri(path: '/target');
        $headers  = new HeaderCollection();
        $response = $this->createMock(ResponseContract::class);
        $response->expects(self::once())
            ->method('withStatusCode')
            ->with(StatusCode::MOVED_PERMANENTLY)
            ->willReturnSelf();

        $exception = new HttpRedirectResponseException(
            $uri,
            StatusCode::MOVED_PERMANENTLY,
            $headers,
            $response,
        );

        self::assertSame($uri, $exception->getUri());
        self::assertSame(StatusCode::MOVED_PERMANENTLY, $exception->getStatusCode());
        self::assertSame($response, $exception->getResponse());
    }
}