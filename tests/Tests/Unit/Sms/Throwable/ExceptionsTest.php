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

namespace Valkyrja\Tests\Unit\Sms\Throwable;

use Valkyrja\Sms\Throwable\Contract\SmsThrowable;
use Valkyrja\Sms\Throwable\Exception\Abstract\SmsInvalidArgumentException;
use Valkyrja\Sms\Throwable\Exception\Abstract\SmsRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;

final class ExceptionsTest extends TestCase
{
    public function testThrowableInterfaceExtendsValkyrjaThrowable(): void
    {
        self::assertTrue(is_a(SmsThrowable::class, ValkyrjaThrowable::class, true));
    }

    public function testExceptionHierarchy(): void
    {
        // Both implement Throwable
        self::assertTrue(is_a(SmsRuntimeException::class, SmsThrowable::class, true));
        self::assertTrue(is_a(SmsInvalidArgumentException::class, SmsThrowable::class, true));
    }
}
