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

namespace Valkyrja\Tests\Unit\Log\Throwable;

use Valkyrja\Log\Throwable\Contract\LogThrowable;
use Valkyrja\Log\Throwable\Exception\Abstract\LogInvalidArgumentException;
use Valkyrja\Log\Throwable\Exception\Abstract\LogRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(LogThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testExceptionHierarchy(): void
    {
        self::assertTrue(is_a(LogInvalidArgumentException::class, LogThrowable::class, true));
        self::assertTrue(is_a(LogRuntimeException::class, LogThrowable::class, true));
    }
}
