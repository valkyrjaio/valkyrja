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
use Valkyrja\Http\Message\Throwable\Exception\HttpNotFoundResponseException;
use Valkyrja\Http\Message\Throwable\Exception\HttpResponseException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class HttpNotFoundResponseExceptionTest extends TestCase
{
    public function testExtendsHttpResponseException(): void
    {
        self::assertInstanceOf(HttpResponseException::class, new HttpNotFoundResponseException());
    }

    public function testDefaultsToNotFoundStatusCode(): void
    {
        $exception = new HttpNotFoundResponseException();

        self::assertSame(StatusCode::NOT_FOUND, $exception->getStatusCode());
    }

    public function testUsesProvidedStatusCodeAndMessage(): void
    {
        $exception = new HttpNotFoundResponseException(StatusCode::GONE, 'Gone');

        self::assertSame(StatusCode::GONE, $exception->getStatusCode());
        self::assertSame('Gone', $exception->getMessage());
    }
}