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

namespace Valkyrja\Tests\Unit\Type\Model;

use Throwable as PHPThrowable;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Throwable\Exception\Exception as ValkyrjaException;
use Valkyrja\Type\Model\Throwable\Contract\Throwable;
use Valkyrja\Type\Model\Throwable\Exception\Exception;
use Valkyrja\Type\Model\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Type\Model\Throwable\Exception\RuntimeException;
use Valkyrja\Type\Throwable\Contract\Throwable as TypeThrowable;
use Valkyrja\Type\Throwable\Exception\InvalidArgumentException as TypeInvalidArgumentException;
use Valkyrja\Type\Throwable\Exception\RuntimeException as TypeRuntimeException;

final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(PHPThrowable::class, Throwable::class);
        self::isA(TypeThrowable::class, Throwable::class);
    }

    public function testException(): void
    {
        self::isA(Throwable::class, Exception::class);
        self::isA(ValkyrjaException::class, Exception::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(Throwable::class, InvalidArgumentException::class);
        self::isA(TypeInvalidArgumentException::class, InvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(Throwable::class, RuntimeException::class);
        self::isA(TypeRuntimeException::class, RuntimeException::class);
    }
}
