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

namespace Valkyrja\Tests\Unit\View\Throwable;

use Throwable as PHPThrowable;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\Throwable as BaseThrowable;
use Valkyrja\Throwable\Exception\InvalidArgumentException as BaseInvalidArgumentException;
use Valkyrja\Throwable\Exception\RuntimeException as BaseRuntimeException;
use Valkyrja\View\Throwable\Contract\Throwable;
use Valkyrja\View\Throwable\Exception\InvalidArgumentException;
use Valkyrja\View\Throwable\Exception\InvalidConfigPath;
use Valkyrja\View\Throwable\Exception\RuntimeException;

/**
 * Test the View exceptions.
 */
class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(PHPThrowable::class, Throwable::class);
        self::isA(BaseThrowable::class, Throwable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(Throwable::class, InvalidArgumentException::class);
        self::isA(BaseInvalidArgumentException::class, InvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(Throwable::class, RuntimeException::class);
        self::isA(BaseRuntimeException::class, RuntimeException::class);
    }

    public function testInvalidConfigPath(): void
    {
        self::isA(Throwable::class, InvalidConfigPath::class);
        self::isA(BaseInvalidArgumentException::class, InvalidConfigPath::class);
    }
}
