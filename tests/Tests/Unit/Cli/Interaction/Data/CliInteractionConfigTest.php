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