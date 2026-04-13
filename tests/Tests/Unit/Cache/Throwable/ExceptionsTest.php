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

namespace Valkyrja\Tests\Unit\Cache\Throwable;

use Valkyrja\Cache\Throwable\Contract\CacheThrowable;
use Valkyrja\Cache\Throwable\Exception\Abstract\CacheInvalidArgumentException;
use Valkyrja\Cache\Throwable\Exception\Abstract\CacheRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(CacheThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testExceptionHierarchy(): void
    {
        self::assertTrue(is_a(CacheRuntimeException::class, CacheThrowable::class, true));
        self::assertTrue(is_a(CacheInvalidArgumentException::class, CacheThrowable::class, true));
    }
}
