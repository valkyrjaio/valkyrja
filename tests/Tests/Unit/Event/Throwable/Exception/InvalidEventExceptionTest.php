<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Event\Throwable\Exception;

use Valkyrja\Event\Throwable\Exception\InvalidEventException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class InvalidEventExceptionTest extends TestCase
{
    public function testMessage(): void
    {
        $id = self::class;

        $exception = new InvalidEventException($id);

        self::assertSame("Service with `$id` is not an event", $exception->getMessage());
    }
}
