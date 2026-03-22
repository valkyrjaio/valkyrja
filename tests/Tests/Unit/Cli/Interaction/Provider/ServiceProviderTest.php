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
use Valkyrja\Application\Data\Config;
use Valkyrja\Cli\Interaction\Data\Contract\ConfigContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
use Valkyrja\Cli\Interaction\Provider\ServiceProvider;
use Valkyrja\Tests\Classes\Cli\Interaction\Data\ConfigClass;
use Valkyrja\Tests\Unit\Container\Provider\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(ConfigContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(OutputFactoryContract::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(ConfigContract::class, ServiceProvider::provides());
        self::assertContains(OutputFactoryContract::class, ServiceProvider::provides());
    }

    public function testPublishConfig(): void
    {
        $callback = ServiceProvider::publishers()[ConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(ConfigContract::class, $config = $this->container->getSingleton(ConfigContract::class));
        self::assertFalse($config->isQuiet);
        self::assertTrue($config->isInteractive);
        self::assertFalse($config->isSilent);
    }

    public function testPublishConfigWithApplicationConfig(): void
    {
        $this->container->setSingleton(Config::class, new ConfigClass(
            isQuiet: true,
            isInteractive: false,
            isSilent: true,
        ));

        $callback = ServiceProvider::publishers()[ConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(ConfigContract::class, $config = $this->container->getSingleton(ConfigContract::class));
        self::assertTrue($config->isQuiet);
        self::assertFalse($config->isInteractive);
        self::assertTrue($config->isSilent);
    }

    /**
     * @throws Exception
     */
    public function testPublishOutputFactory(): void
    {
        $this->container->setSingleton(ConfigContract::class, self::createStub(ConfigContract::class));

        $callback = ServiceProvider::publishers()[OutputFactoryContract::class];
        $callback($this->container);

        self::assertInstanceOf(OutputFactory::class, $this->container->getSingleton(OutputFactoryContract::class));
    }
}
