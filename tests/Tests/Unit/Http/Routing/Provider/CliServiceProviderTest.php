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

use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Env\Env;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Http\Routing\Cli\Command\GenerateDataCommand;
use Valkyrja\Http\Routing\Provider\CliServiceProvider;
use Valkyrja\Http\Routing\Provider\ServiceProvider;
use Valkyrja\Tests\Unit\Container\Provider\Abstract\ServiceProviderTestCase;

/**
 * Test the CliServiceProviderTest.
 */
final class CliServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(GenerateDataCommand::class, CliServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(GenerateDataCommand::class, CliServiceProvider::provides());
    }

    public function testGenerateDataCommand(): void
    {
        $container = $this->container;

        $container->setSingleton(Env::class, self::createStub(Env::class));
        $container->setSingleton(HttpConfig::class, self::createStub(HttpConfig::class));
        $container->setSingleton(OutputFactoryContract::class, self::createStub(OutputFactoryContract::class));

        self::assertFalse($container->has(GenerateDataCommand::class));

        $callback = CliServiceProvider::publishers()[GenerateDataCommand::class];
        $callback($this->container);

        self::assertTrue($container->has(GenerateDataCommand::class));
        self::assertTrue($container->isSingleton(GenerateDataCommand::class));
        self::assertInstanceOf(GenerateDataCommand::class, $container->getSingleton(GenerateDataCommand::class));
    }
}
