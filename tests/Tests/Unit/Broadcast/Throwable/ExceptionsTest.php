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

namespace Valkyrja\Tests\Unit\Broadcast\Throwable;

use Valkyrja\Broadcast\Throwable\Contract\BroadcastThrowable;
use Valkyrja\Broadcast\Throwable\Exception\Abstract\BroadcastInvalidArgumentException;
use Valkyrja\Broadcast\Throwable\Exception\Abstract\BroadcastRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(BroadcastThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testExceptionHierarchy(): void
    {
        // Both implement Throwable
        self::assertTrue(is_a(BroadcastRuntimeException::class, BroadcastThrowable::class, true));
        self::assertTrue(is_a(BroadcastInvalidArgumentException::class, BroadcastThrowable::class, true));
    }
}
