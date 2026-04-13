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

namespace Valkyrja\Tests\Unit\Container\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Generator\Contract\DataFileGeneratorContract;
use Valkyrja\Container\Generator\DataFileGenerator;
use Valkyrja\Container\Provider\ContainerServiceProvider;
use Valkyrja\Tests\Unit\Container\Provider\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ContainerServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(DataFileGeneratorContract::class, ContainerServiceProvider::publishers());
        self::assertArrayHasKey(ContainerData::class, ContainerServiceProvider::publishers());
    }

    /**
     * @throws Exception
     */
    public function testPublishDataFileGenerator(): void
    {
        $this->container->setSingleton(ContainerData::class, new ContainerData());

        $callback = ContainerServiceProvider::publishers()[DataFileGeneratorContract::class];
        $callback($this->container);

        self::assertInstanceOf(DataFileGenerator::class, $this->container->getSingleton(DataFileGeneratorContract::class));
    }
}
