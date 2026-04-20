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

namespace Valkyrja\Tests\Unit\Http\Routing\Provider;

use Valkyrja\Application\Data\Contract\HttpConfigContract;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Env\Env;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Http\Routing\Cli\Command\GenerateDataCommand;
use Valkyrja\Http\Routing\Cli\Command\ListCommand;
use Valkyrja\Http\Routing\Provider\HttpRoutingCliServiceProvider;
use Valkyrja\Http\Routing\Provider\HttpRoutingServiceProvider;
use Valkyrja\Tests\Unit\Container\Provider\Abstract\ServiceProviderTestCase;

/**
 * Test the CliServiceProviderTest.
 */
final class CliServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = HttpRoutingServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(GenerateDataCommand::class, HttpRoutingCliServiceProvider::publishers());
        self::assertArrayHasKey(ListCommand::class, HttpRoutingCliServiceProvider::publishers());
    }

    public function testGenerateDataCommand(): void
    {
        $container = $this->container;

        $container->setSingleton(Env::class, self::createStub(Env::class));
        $container->setSingleton(HttpConfigContract::class, self::createStub(HttpConfig::class));
        $container->setSingleton(OutputFactoryContract::class, self::createStub(OutputFactoryContract::class));

        self::assertFalse($container->has(GenerateDataCommand::class));

        $callback = HttpRoutingCliServiceProvider::publishers()[GenerateDataCommand::class];
        $callback($this->container);

        self::assertTrue($container->has(GenerateDataCommand::class));
        self::assertTrue($container->isSingleton(GenerateDataCommand::class));
        self::assertInstanceOf(GenerateDataCommand::class, $container->getSingleton(GenerateDataCommand::class));
    }

    public function testListCommand(): void
    {
        $container = $this->container;

        self::assertFalse($container->has(ListCommand::class));

        $callback = HttpRoutingCliServiceProvider::publishers()[ListCommand::class];
        $callback($this->container);

        self::assertTrue($container->has(ListCommand::class));
        self::assertTrue($container->isSingleton(ListCommand::class));
        self::assertInstanceOf(ListCommand::class, $container->getSingleton(ListCommand::class));
    }
}
