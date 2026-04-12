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

use Throwable;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Model\Throwable\Contract\ModelThrowable;
use Valkyrja\Type\Model\Throwable\Exception\ModelInvalidArgumentException;
use Valkyrja\Type\Model\Throwable\Exception\ModelRuntimeException;
use Valkyrja\Type\Throwable\Contract\TypeThrowable;
use Valkyrja\Type\Throwable\Exception\TypeInvalidArgumentException;
use Valkyrja\Type\Throwable\Exception\TypeRuntimeException;

final class ExceptionsTest extends TestCase
{
    public function testThrowable(): void
    {
        self::isA(Throwable::class, ModelThrowable::class);
        self::isA(TypeThrowable::class, ModelThrowable::class);
    }

    public function testInvalidArgumentException(): void
    {
        self::isA(ModelThrowable::class, ModelInvalidArgumentException::class);
        self::isA(TypeInvalidArgumentException::class, ModelInvalidArgumentException::class);
    }

    public function testRuntimeException(): void
    {
        self::isA(ModelThrowable::class, ModelRuntimeException::class);
        self::isA(TypeRuntimeException::class, ModelRuntimeException::class);
    }
}
