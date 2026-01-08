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
use Valkyrja\Application\Env\Env;
use Valkyrja\Cli\Interaction\Data\Config;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Factory\OutputFactory;
use Valkyrja\Cli\Interaction\Provider\ServiceProvider;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
class ServiceProviderTest extends ServiceProviderTestCase
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
        self::assertSame(Env::CLI_INTERACTION_IS_QUIET, $config->isQuiet);
        self::assertSame(Env::CLI_INTERACTION_IS_INTERACTIVE, $config->isInteractive);
        self::assertSame(Env::CLI_INTERACTION_IS_SILENT, $config->isSilent);
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
