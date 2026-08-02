<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Routing\Data\Option;

use Valkyrja\Cli\Routing\Data\Option\QuietOptionParameter;
use Valkyrja\Cli\Routing\Enum\OptionMode;
use Valkyrja\Cli\Routing\Enum\OptionValueMode;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class QuietOptionParameterTest extends TestCase
{
    public function testValues(): void
    {
        $option = new QuietOptionParameter();

        self::assertSame('quiet', $option->getName());
        self::assertSame('Output is suppressed, except for errors.', $option->getDescription());
        self::assertSame(['q'], $option->getShortNames());
        self::assertSame(OptionValueMode::NONE, $option->getValueMode());
        self::assertFalse($option->hasValueDisplayName());
        self::assertFalse($option->hasDefaultValue());
        self::assertFalse($option->hasCast());
        self::assertEmpty($option->getValidValues());
        self::assertSame(OptionMode::OPTIONAL, $option->getMode());
    }
}
