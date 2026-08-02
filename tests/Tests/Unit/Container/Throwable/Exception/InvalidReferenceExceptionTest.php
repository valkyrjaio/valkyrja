<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Container\Throwable\Exception;

use Valkyrja\Container\Throwable\Exception\ContainerInvalidReferenceException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class InvalidReferenceExceptionTest extends TestCase
{
    public function testMessage(): void
    {
        $id = self::class;

        $exception = new ContainerInvalidReferenceException($id);

        self::assertSame("Service with `$id` not found", $exception->getMessage());
    }
}
