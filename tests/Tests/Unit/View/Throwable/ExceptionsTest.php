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

use Throwable;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaInvalidArgumentException;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaRuntimeException;
use Valkyrja\View\Throwable\Contract\ViewThrowable;
use Valkyrja\View\Throwable\Exception\Abstract\ViewInvalidArgumentException;
use Valkyrja\View\Throwable\Exception\Abstract\ViewRuntimeException;
use Valkyrja\View\Throwable\Exception\ViewInvalidPathException;

/**
 * Test the View exceptions.
 */
final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(Throwable::class, ViewThrowable::class);
        self::isA(ValkyrjaThrowable::class, ViewThrowable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(ViewThrowable::class, ViewInvalidArgumentException::class);
        self::isA(ValkyrjaInvalidArgumentException::class, ViewInvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(ViewThrowable::class, ViewRuntimeException::class);
        self::isA(ValkyrjaRuntimeException::class, ViewRuntimeException::class);
    }

    public function testInvalidConfigPath(): void
    {
        self::isA(ViewThrowable::class, ViewInvalidPathException::class);
        self::isA(ValkyrjaInvalidArgumentException::class, ViewInvalidPathException::class);
    }
}
