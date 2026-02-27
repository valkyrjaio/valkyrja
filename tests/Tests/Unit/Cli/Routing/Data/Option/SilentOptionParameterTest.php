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

namespace Valkyrja\Tests\Unit\Cli\Routing\Data\Option;

use Valkyrja\Cli\Routing\Data\Option\SilentOptionParameter;
use Valkyrja\Cli\Routing\Enum\OptionMode;
use Valkyrja\Cli\Routing\Enum\OptionValueMode;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class SilentOptionParameterTest extends TestCase
{
    public function testValues(): void
    {
        $option = new SilentOptionParameter();

        self::assertSame('silent', $option->getName());
        self::assertSame('All output is suppressed', $option->getDescription());
        self::assertSame(['s'], $option->getShortNames());
        self::assertSame(OptionValueMode::NONE, $option->getValueMode());
        self::assertFalse($option->hasValueDisplayName());
        self::assertFalse($option->hasDefaultValue());
        self::assertFalse($option->hasCast());
        self::assertEmpty($option->getValidValues());
        self::assertSame(OptionMode::OPTIONAL, $option->getMode());
    }
}
