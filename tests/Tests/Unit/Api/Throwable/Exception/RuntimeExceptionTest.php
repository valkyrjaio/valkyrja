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
use Valkyrja\Api\Throwable\Exception\ApiRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaRuntimeException;

/**
 * Test the RuntimeException.
 */
final class RuntimeExceptionTest extends TestCase
{
    public function testImplementsThrowable(): void
    {
        $exception = new ApiRuntimeException();

        self::assertInstanceOf(ApiThrowable::class, $exception);
    }

    public function testExtendsBaseRuntimeException(): void
    {
        $exception = new ApiRuntimeException();

        self::assertInstanceOf(ValkyrjaRuntimeException::class, $exception);
    }

    public function testCanBeThrown(): void
    {
        $this->expectException(ApiRuntimeException::class);
        $this->expectExceptionMessage('Test error');

        throw new ApiRuntimeException('Test error');
    }

    public function testCanBeCaughtAsThrowable(): void
    {
        $caught = false;

        try {
            throw new ApiRuntimeException('Caught as throwable');
        } catch (ApiThrowable) {
            $caught = true;
        }

        self::assertTrue($caught);
    }

    public function testExceptionCode(): void
    {
        $exception = new ApiRuntimeException('Error', 42);

        self::assertSame(42, $exception->getCode());
    }
}
