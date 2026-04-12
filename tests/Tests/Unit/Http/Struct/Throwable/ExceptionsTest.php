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

namespace Valkyrja\Tests\Unit\Http\Struct\Throwable;

use Throwable;
use Valkyrja\Http\Struct\Throwable\Contract\HttpStructThrowable;
use Valkyrja\Http\Struct\Throwable\Exception\HttpStructInvalidArgumentException;
use Valkyrja\Http\Struct\Throwable\Exception\HttpStructRuntimeException;
use Valkyrja\Http\Throwable\Contract\HttpThrowable;
use Valkyrja\Http\Throwable\Exception\HttpInvalidArgumentException;
use Valkyrja\Http\Throwable\Exception\HttpRuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(Throwable::class, HttpStructThrowable::class);
        self::isA(HttpThrowable::class, HttpStructThrowable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(HttpStructThrowable::class, HttpStructInvalidArgumentException::class);
        self::isA(HttpInvalidArgumentException::class, HttpStructInvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(HttpStructThrowable::class, HttpStructRuntimeException::class);
        self::isA(HttpRuntimeException::class, HttpStructRuntimeException::class);
    }
}
