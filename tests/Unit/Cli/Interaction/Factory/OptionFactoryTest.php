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

namespace Valkyrja\Tests\Unit\Cli\Interaction\Factory;

use Valkyrja\Cli\Interaction\Enum\OptionType;
use Valkyrja\Cli\Interaction\Exception\InvalidArgumentException;
use Valkyrja\Cli\Interaction\Factory\OptionFactory;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the OptionFactory class.
 *
 * @author Melech Mizrachi
 */
class OptionFactoryTest extends TestCase
{
    public function testFromArgInvalidArg(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $arg = 'value';

        OptionFactory::fromArg(arg: $arg);
    }

    public function testFromArgLongOption(): void
    {
        $arg = '--value';

        $options = OptionFactory::fromArg(arg: $arg);

        self::assertCount(1, $options);
        self::assertSame('value', $options[0]->getName());
        self::assertSame(OptionType::LONG, $options[0]->getType());
        self::assertNull($options[0]->getValue());
    }

    public function testFromArgLongOptionWithValue(): void
    {
        $arg = '--value2=something';

        $options = OptionFactory::fromArg(arg: $arg);

        self::assertCount(1, $options);
        self::assertSame('value2', $options[0]->getName());
        self::assertSame(OptionType::LONG, $options[0]->getType());
        self::assertSame('something', $options[0]->getValue());
    }

    public function testFromArgLongOptionWithEmptyValue(): void
    {
        $arg = '--value=';

        $options = OptionFactory::fromArg(arg: $arg);

        self::assertCount(1, $options);
        self::assertSame('value', $options[0]->getName());
        self::assertSame(OptionType::LONG, $options[0]->getType());
        self::assertNull($options[0]->getValue());
    }

    public function testFromArgShortOption(): void
    {
        $arg = '-v';

        $options = OptionFactory::fromArg(arg: $arg);

        self::assertCount(1, $options);
        self::assertSame('v', $options[0]->getName());
        self::assertSame(OptionType::SHORT, $options[0]->getType());
        self::assertNull($options[0]->getValue());
    }

    public function testFromArgShortOptionWithValue(): void
    {
        $arg = '-v=value';

        $options = OptionFactory::fromArg(arg: $arg);

        self::assertCount(1, $options);
        self::assertSame('v', $options[0]->getName());
        self::assertSame(OptionType::SHORT, $options[0]->getType());
        self::assertSame('value', $options[0]->getValue());
    }

    public function testFromArgMultipleShortOptions(): void
    {
        $value3 = '-vwf';

        $options = OptionFactory::fromArg(arg: $value3);

        self::assertCount(3, $options);

        self::assertSame('v', $options[0]->getName());
        self::assertSame(OptionType::SHORT, $options[0]->getType());
        self::assertNull($options[0]->getValue());

        self::assertSame('w', $options[1]->getName());
        self::assertSame(OptionType::SHORT, $options[1]->getType());
        self::assertNull($options[1]->getValue());

        self::assertSame('f', $options[2]->getName());
        self::assertSame(OptionType::SHORT, $options[2]->getType());
        self::assertNull($options[2]->getValue());
    }

    public function testFromArgMultipleShortOptionsWithValueException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $value3 = '-vwf=value';

        OptionFactory::fromArg(arg: $value3);
    }
}
