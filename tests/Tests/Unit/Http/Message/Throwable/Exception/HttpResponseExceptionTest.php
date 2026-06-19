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
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Throwable\Exception\HttpResponseException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class HttpResponseExceptionTest extends TestCase
{
    public function testDefaultsToInternalServerErrorWithEmptyHeadersAndNoResponse(): void
    {
        $exception = new HttpResponseException();

        self::assertSame(StatusCode::INTERNAL_SERVER_ERROR, $exception->getStatusCode());
        self::assertInstanceOf(HeaderCollection::class, $exception->getHeaders());
        self::assertNull($exception->getResponse());
        self::assertSame('', $exception->getMessage());
    }

    public function testUsesProvidedStatusCodeMessageAndHeaders(): void
    {
        $headers = new HeaderCollection();

        $exception = new HttpResponseException(StatusCode::NOT_FOUND, 'Nope', $headers);

        self::assertSame(StatusCode::NOT_FOUND, $exception->getStatusCode());
        self::assertSame('Nope', $exception->getMessage());
        self::assertSame($headers, $exception->getHeaders());
        self::assertNull($exception->getResponse());
    }

    public function testDerivesStatusCodeFromResponseWhenNoneGiven(): void
    {
        $response = $this->createMock(ResponseContract::class);
        $response->method('getStatusCode')->willReturn(StatusCode::FOUND);
        $response->expects(self::once())
            ->method('withStatusCode')
            ->with(StatusCode::FOUND)
            ->willReturnSelf();

        $exception = new HttpResponseException(response: $response);

        self::assertSame(StatusCode::FOUND, $exception->getStatusCode());
        self::assertSame($response, $exception->getResponse());
    }
}