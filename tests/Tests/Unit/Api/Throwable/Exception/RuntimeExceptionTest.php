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

use Valkyrja\Api\Throwable\Contract\Throwable;
use Valkyrja\Api\Throwable\Exception\RuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Exception\RuntimeException as BaseRuntimeException;

/**
 * Test the RuntimeException.
 */
class RuntimeExceptionTest extends TestCase
{
    public function testImplementsThrowable(): void
    {
        $exception = new RuntimeException();

        self::assertInstanceOf(Throwable::class, $exception);
    }

    public function testExtendsBaseRuntimeException(): void
    {
        $exception = new RuntimeException();

        self::assertInstanceOf(BaseRuntimeException::class, $exception);
    }

    public function testCanBeThrown(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Test error');

        throw new RuntimeException('Test error');
    }

    public function testCanBeCaughtAsThrowable(): void
    {
        $caught = false;

        try {
            throw new RuntimeException('Caught as throwable');
        } catch (Throwable) {
            $caught = true;
        }

        self::assertTrue($caught);
    }

    public function testExceptionCode(): void
    {
        $exception = new RuntimeException('Error', 42);

        self::assertSame(42, $exception->getCode());
    }
}
