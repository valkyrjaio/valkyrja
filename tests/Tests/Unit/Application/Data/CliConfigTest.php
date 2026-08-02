<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
        self::assertSame([], $config->processExitingMiddleware);
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
