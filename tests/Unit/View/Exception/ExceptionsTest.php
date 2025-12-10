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

namespace Valkyrja\Tests\Unit\View\Exception;

use Throwable as PHPThrowable;
use Valkyrja\Exception\InvalidArgumentException as BaseInvalidArgumentException;
use Valkyrja\Exception\RuntimeException as BaseRuntimeException;
use Valkyrja\Exception\Throwable as BaseThrowable;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\View\Exception\InvalidArgumentException;
use Valkyrja\View\Exception\InvalidConfigPath;
use Valkyrja\View\Exception\RuntimeException;
use Valkyrja\View\Exception\Throwable;

/**
 * Test the View exceptions.
 *
 * @author Melech Mizrachi
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
