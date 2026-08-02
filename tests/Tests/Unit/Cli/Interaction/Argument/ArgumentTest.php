<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Interaction\Argument;

use Valkyrja\Cli\Interaction\Argument\Argument;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Argument class.
 */
final class ArgumentTest extends TestCase
{
    public function testValue(): void
    {
        $value    = 'value';
        $newValue = 'value2';

        $argument = new Argument(value: $value);

        self::assertSame($value, $argument->getValue());

        $argument2 = $argument->withValue($newValue);

        self::assertNotSame($argument, $argument2);
        self::assertSame($newValue, $argument2->getValue());
    }
}
