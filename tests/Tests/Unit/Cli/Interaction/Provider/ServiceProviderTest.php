<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Interaction\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Cli\Interaction\Data\Contract\CliInteractionConfigContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
use Valkyrja\Cli\Interaction\Provider\CliInteractionServiceProvider;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;
use Valkyrja\Tests\Fixtures\Cli\Interaction\Data\CliInteractionConfigFixture;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = CliInteractionServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(CliInteractionConfigContract::class, new CliInteractionServiceProvider()->publishers());
        self::assertArrayHasKey(OutputFactoryContract::class, new CliInteractionServiceProvider()->publishers());
    }

    public function testPublishConfig(): void
    {
        $callback = new CliInteractionServiceProvider()->publishers()[CliInteractionConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(CliInteractionConfigContract::class, $config = $this->container->getSingleton(CliInteractionConfigContract::class));
        self::assertFalse($config->isQuiet);
        self::assertTrue($config->isInteractive);
        self::assertFalse($config->isSilent);
    }

    public function testPublishConfigWithApplicationConfig(): void
    {
        $this->container->setSingleton(ConfigContract::class, new CliInteractionConfigFixture(
            isQuiet: true,
            isInteractive: false,
            isSilent: true,
        ));

        $callback = new CliInteractionServiceProvider()->publishers()[CliInteractionConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(CliInteractionConfigContract::class, $config = $this->container->getSingleton(CliInteractionConfigContract::class));
        self::assertTrue($config->isQuiet);
        self::assertFalse($config->isInteractive);
        self::assertTrue($config->isSilent);
    }

    /**
     * @throws Exception
     */
    public function testPublishOutputFactory(): void
    {
        $this->container->setSingleton(CliInteractionConfigContract::class, self::createStub(CliInteractionConfigContract::class));

        $callback = new CliInteractionServiceProvider()->publishers()[OutputFactoryContract::class];
        $callback($this->container);

        self::assertInstanceOf(OutputFactory::class, $this->container->getSingleton(OutputFactoryContract::class));
    }
}
