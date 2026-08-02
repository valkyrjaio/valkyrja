<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Interaction\Data;

use Valkyrja\Cli\Interaction\Data\CliInteractionConfig;
use Valkyrja\Cli\Interaction\Data\Contract\CliInteractionConfigContract;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class CliInteractionConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(CliInteractionConfigContract::class, new CliInteractionConfig());
    }

    public function testDefaults(): void
    {
        $config = new CliInteractionConfig();

        self::assertFalse($config->isQuiet);
        self::assertTrue($config->isInteractive);
        self::assertFalse($config->isSilent);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new CliInteractionConfig(
            isQuiet: true,
            isInteractive: false,
            isSilent: true,
        );

        self::assertTrue($config->isQuiet);
        self::assertFalse($config->isInteractive);
        self::assertTrue($config->isSilent);
    }
}
