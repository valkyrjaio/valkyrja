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

namespace Valkyrja\Tests\Unit\Cli\Interaction\Argument;

use Valkyrja\Cli\Interaction\Argument\Argument;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Argument class.
 */
class ArgumentTest extends TestCase
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
