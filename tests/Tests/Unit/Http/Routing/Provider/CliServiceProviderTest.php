<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Routing\Provider;

use Valkyrja\Http\Routing\Cli\Command\ListCommand;
use Valkyrja\Http\Routing\Provider\HttpRoutingCliServiceProvider;
use Valkyrja\Http\Routing\Provider\HttpRoutingServiceProvider;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;

/**
 * Test the CliServiceProviderTest.
 */
final class CliServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = HttpRoutingServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(ListCommand::class, new HttpRoutingCliServiceProvider()->publishers());
    }

    public function testListCommand(): void
    {
        $container = $this->container;

        self::assertFalse($container->has(ListCommand::class));

        $callback = new HttpRoutingCliServiceProvider()->publishers()[ListCommand::class];
        $callback($this->container);

        self::assertTrue($container->has(ListCommand::class));
        self::assertTrue($container->isSingleton(ListCommand::class));
        self::assertInstanceOf(ListCommand::class, $container->getSingleton(ListCommand::class));
    }
}
