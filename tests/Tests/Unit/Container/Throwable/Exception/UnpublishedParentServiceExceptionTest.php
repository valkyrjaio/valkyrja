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

use Valkyrja\Container\Throwable\Exception\ContainerUnpublishedParentServiceException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class UnpublishedParentServiceExceptionTest extends TestCase
{
    public function testMessage(): void
    {
        $id = self::class;

        $exception = new ContainerUnpublishedParentServiceException($id);

        self::assertSame(
            "Service `$id` is registered in the parent container and its publish callback has not run. "
            . 'Force-resolve it in bootstrapParentServices(), or give the child the publish callbacks.',
            $exception->getMessage()
        );
    }
}
