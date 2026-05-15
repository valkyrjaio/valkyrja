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

namespace Valkyrja\Tests\Unit\Cli\Interaction\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Cli\Interaction\Data\Contract\CliInteractionConfigContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
use Valkyrja\Cli\Interaction\Provider\CliInteractionServiceProvider;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;
use Valkyrja\Tests\Classes\Cli\Interaction\Data\CliInteractionConfigClass;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = CliInteractionServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(CliInteractionConfigContract::class, (new CliInteractionServiceProvider())->publishers());
        self::assertArrayHasKey(OutputFactoryContract::class, (new CliInteractionServiceProvider())->publishers());
    }

    public function testPublishConfig(): void
    {
        $callback = (new CliInteractionServiceProvider())->publishers()[CliInteractionConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(CliInteractionConfigContract::class, $config = $this->container->getSingleton(CliInteractionConfigContract::class));
        self::assertFalse($config->isQuiet);
        self::assertTrue($config->isInteractive);
        self::assertFalse($config->isSilent);
    }

    public function testPublishConfigWithApplicationConfig(): void
    {
        $this->container->setSingleton(ConfigContract::class, new CliInteractionConfigClass(
            isQuiet: true,
            isInteractive: false,
            isSilent: true,
        ));

        $callback = (new CliInteractionServiceProvider())->publishers()[CliInteractionConfigContract::class];
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

        $callback = (new CliInteractionServiceProvider())->publishers()[OutputFactoryContract::class];
        $callback($this->container);

        self::assertInstanceOf(OutputFactory::class, $this->container->getSingleton(OutputFactoryContract::class));
    }
}
