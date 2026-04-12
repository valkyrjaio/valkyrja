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

namespace Valkyrja\Tests\Unit\Api\Throwable\Exception;

use Valkyrja\Api\Throwable\Contract\ApiThrowable;
use Valkyrja\Api\Throwable\Exception\ApiInvalidArgumentException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaInvalidArgumentException;

/**
 * Test the InvalidArgumentException.
 */
final class InvalidArgumentExceptionTest extends TestCase
{
    public function testImplementsThrowable(): void
    {
        $exception = new ApiInvalidArgumentException();

        self::assertInstanceOf(ApiThrowable::class, $exception);
    }

    public function testExtendsBaseInvalidArgumentException(): void
    {
        $exception = new ApiInvalidArgumentException();

        self::assertInstanceOf(ValkyrjaInvalidArgumentException::class, $exception);
    }

    public function testCanBeThrown(): void
    {
        $this->expectException(ApiInvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument');

        throw new ApiInvalidArgumentException('Invalid argument');
    }

    public function testCanBeCaughtAsThrowable(): void
    {
        $caught = false;

        try {
            throw new ApiInvalidArgumentException('Caught as throwable');
        } catch (ApiThrowable) {
            $caught = true;
        }

        self::assertTrue($caught);
    }

    public function testExceptionCode(): void
    {
        $exception = new ApiInvalidArgumentException('Error', 100);

        self::assertSame(100, $exception->getCode());
    }
}
