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
use Valkyrja\Api\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Exception\InvalidArgumentException as BaseInvalidArgumentException;

/**
 * Test the InvalidArgumentException.
 */
final class InvalidArgumentExceptionTest extends TestCase
{
    public function testImplementsThrowable(): void
    {
        $exception = new InvalidArgumentException();

        self::assertInstanceOf(Throwable::class, $exception);
    }

    public function testExtendsBaseInvalidArgumentException(): void
    {
        $exception = new InvalidArgumentException();

        self::assertInstanceOf(BaseInvalidArgumentException::class, $exception);
    }

    public function testCanBeThrown(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument');

        throw new InvalidArgumentException('Invalid argument');
    }

    public function testCanBeCaughtAsThrowable(): void
    {
        $caught = false;

        try {
            throw new InvalidArgumentException('Caught as throwable');
        } catch (Throwable) {
            $caught = true;
        }

        self::assertTrue($caught);
    }

    public function testExceptionCode(): void
    {
        $exception = new InvalidArgumentException('Error', 100);

        self::assertSame(100, $exception->getCode());
    }
}
