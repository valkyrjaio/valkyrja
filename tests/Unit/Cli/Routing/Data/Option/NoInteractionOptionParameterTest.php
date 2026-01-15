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

use Valkyrja\Cli\Routing\Data\Option\NoInteractionOptionParameter;
use Valkyrja\Cli\Routing\Enum\OptionMode;
use Valkyrja\Cli\Routing\Enum\OptionValueMode;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class NoInteractionOptionParameterTest extends TestCase
{
    public function testValues(): void
    {
        $option = new NoInteractionOptionParameter();

        self::assertSame('no-interaction', $option->getName());
        self::assertSame('No interactive questions are asked.', $option->getDescription());
        self::assertSame(['N'], $option->getShortNames());
        self::assertSame(OptionValueMode::NONE, $option->getValueMode());
        self::assertNull($option->getValueDisplayName());
        self::assertNull($option->getDefaultValue());
        self::assertNull($option->getCast());
        self::assertEmpty($option->getValidValues());
        self::assertSame(OptionMode::OPTIONAL, $option->getMode());
    }
}
