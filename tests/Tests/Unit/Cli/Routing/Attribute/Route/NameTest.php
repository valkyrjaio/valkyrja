<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Routing\Attribute\Route;

use Valkyrja\Cli\Routing\Attribute\Route\Name;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class NameTest extends TestCase
{
    public function testValue(): void
    {
        $value = 'foo';
        $name  = new Name(value: $value);

        self::assertSame($value, $name->value);
    }
}
