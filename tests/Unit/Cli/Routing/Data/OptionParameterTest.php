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

namespace Valkyrja\Tests\Unit\Cli\Routing\Data;

use Valkyrja\Cli\Routing\Data\OptionParameter;
use Valkyrja\Cli\Routing\Enum\OptionMode;
use Valkyrja\Cli\Routing\Enum\OptionValueMode;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the OptionParameter class.
 */
class OptionParameterTest extends TestCase
{
    public function testDefaults(): void
    {
        $parameter = new OptionParameter('test-option', 'Test Option');

        self::assertSame('test-option', $parameter->getName());
        self::assertSame('Test Option', $parameter->getDescription());
        self::assertNull($parameter->getCast());
        self::assertNull($parameter->getValueDisplayName());
        self::assertNull($parameter->getDefaultValue());
        self::assertEmpty($parameter->getShortNames());
        self::assertEmpty($parameter->getValidValues());
        self::assertEmpty($parameter->getOptions());
        self::assertSame(OptionMode::OPTIONAL, $parameter->getMode());
        self::assertSame(OptionValueMode::DEFAULT, $parameter->getValueMode());
    }
}
