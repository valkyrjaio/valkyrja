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
use Valkyrja\Cli\Interaction\Data\Config;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
use Valkyrja\Cli\Interaction\Provider\ServiceProvider;
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
        self::assertArrayHasKey(Config::class, ServiceProvider::publishers());
        self::assertArrayHasKey(OutputFactoryContract::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(Config::class, ServiceProvider::provides());
        self::assertContains(OutputFactoryContract::class, ServiceProvider::provides());
    }

    public function testPublishConfig(): void
    {
        $callback = ServiceProvider::publishers()[Config::class];
        $callback($this->container);

        self::assertInstanceOf(Config::class, $config = $this->container->getSingleton(Config::class));
        self::assertFalse($config->isQuiet);
        self::assertTrue($config->isInteractive);
        self::assertFalse($config->isSilent);
    }

    /**
     * @throws Exception
     */
    public function testPublishOutputFactory(): void
    {
        $this->container->setSingleton(Config::class, self::createStub(Config::class));

        $callback = ServiceProvider::publishers()[OutputFactoryContract::class];
        $callback($this->container);

        self::assertInstanceOf(OutputFactory::class, $this->container->getSingleton(OutputFactoryContract::class));
    }
}
