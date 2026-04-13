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

namespace Valkyrja\Tests\Unit\Crypt\Throwable;

use Valkyrja\Crypt\Throwable\Contract\CryptThrowable;
use Valkyrja\Crypt\Throwable\Exception\Abstract\CryptInvalidArgumentException;
use Valkyrja\Crypt\Throwable\Exception\Abstract\CryptRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(CryptThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testExceptionHierarchy(): void
    {
        self::assertTrue(is_a(CryptRuntimeException::class, CryptThrowable::class, true));
        self::assertTrue(is_a(CryptInvalidArgumentException::class, CryptThrowable::class, true));
    }
}
