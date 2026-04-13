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

namespace Valkyrja\Tests\Unit\Jwt\Throwable;

use Valkyrja\Jwt\Throwable\Contract\JwtThrowable;
use Valkyrja\Jwt\Throwable\Exception\Abstract\JwtInvalidArgumentException;
use Valkyrja\Jwt\Throwable\Exception\Abstract\JwtRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(JwtThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testExceptionHierarchy(): void
    {
        self::assertTrue(is_a(JwtRuntimeException::class, JwtThrowable::class, true));
        self::assertTrue(is_a(JwtInvalidArgumentException::class, JwtThrowable::class, true));
    }
}
