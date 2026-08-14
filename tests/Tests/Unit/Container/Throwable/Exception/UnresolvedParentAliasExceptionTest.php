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

use Valkyrja\Container\Throwable\Exception\ContainerUnresolvedParentAliasException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class UnresolvedParentAliasExceptionTest extends TestCase
{
    public function testMessage(): void
    {
        $alias     = self::class;
        $aliasedId = ContainerUnresolvedParentAliasException::class;

        $exception = new ContainerUnresolvedParentAliasException($alias, $aliasedId);

        self::assertSame(
            "Alias `$alias` reaches `$aliasedId`, which the parent container has not resolved. "
            . 'Force-resolve it in bootstrapParentServices() before the request loop begins.',
            $exception->getMessage()
        );
    }
}
