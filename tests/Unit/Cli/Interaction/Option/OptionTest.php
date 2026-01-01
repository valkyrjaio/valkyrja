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

namespace Valkyrja\Tests\Unit\Cli\Interaction\Option;

use Valkyrja\Cli\Interaction\Enum\OptionType;
use Valkyrja\Cli\Interaction\Option\Option;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Option class.
 */
class OptionTest extends TestCase
{
    public function testDefaults(): void
    {
        $name = 'name';

        $option = new Option(name: $name);

        self::assertSame($name, $option->getName());
        self::assertNull($option->getValue());
        self::assertSame(OptionType::LONG, $option->getType());
    }

    public function testName(): void
    {
        $name    = 'name';
        $newName = 'name2';

        $option = new Option(name: $name);

        self::assertSame($name, $option->getName());

        $argument2 = $option->withName($newName);

        self::assertNotSame($option, $argument2);
        self::assertSame($newName, $argument2->getName());
    }

    public function testValue(): void
    {
        $value    = 'value';
        $newValue = 'value2';

        $option = new Option(name: 'name', value: $value);

        self::assertSame($value, $option->getValue());

        $argument2 = $option->withValue($newValue);

        self::assertNotSame($option, $argument2);
        self::assertSame($newValue, $argument2->getValue());
    }

    public function testType(): void
    {
        $type    = OptionType::LONG;
        $newType = OptionType::SHORT;

        $option = new Option(name: 'name', type: $type);

        self::assertSame($type, $option->getType());

        $argument2 = $option->withType($newType);

        self::assertNotSame($option, $argument2);
        self::assertSame($newType, $argument2->getType());
    }
}
