<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
