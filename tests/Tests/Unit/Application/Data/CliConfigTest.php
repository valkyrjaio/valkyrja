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

namespace Valkyrja\Tests\Unit\Application\Data;

use Valkyrja\Application\Data\CliConfig;
use Valkyrja\Application\Data\Contract\CliConfigContract;
use Valkyrja\Cli\Server\Constant\CommandName;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class CliConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(CliConfigContract::class, new CliConfig());
    }

    public function testDefaults(): void
    {
        $config = new CliConfig();

        self::assertSame('App', $config->namespace);
        self::assertSame('production', $config->environment);
        self::assertFalse($config->debugMode);
        self::assertSame('UTC', $config->timezone);
        self::assertSame('valkyrja', $config->applicationName);
        self::assertSame(CommandName::LIST, $config->defaultCommandName);
        self::assertCount(1, $config->providers);
        self::assertCount(3, $config->inputReceivedMiddleware);
        self::assertCount(1, $config->routeNotMatchedMiddleware);
        self::assertCount(2, $config->throwableCaughtMiddleware);
        self::assertSame([], $config->callbacks);
        self::assertSame([], $config->routeMatchedMiddleware);
        self::assertSame([], $config->routeDispatchedMiddleware);
        self::assertSame([], $config->exitedMiddleware);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new CliConfig(
            namespace: 'My',
            debugMode: true,
            environment: 'testing',
            applicationName: 'mycli',
        );

        self::assertSame('My', $config->namespace);
        self::assertTrue($config->debugMode);
        self::assertSame('testing', $config->environment);
        self::assertSame('mycli', $config->applicationName);
    }
}
