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
use Valkyrja\Container\Data\Data;
use Valkyrja\Container\Generator\Contract\DataProviderFileGeneratorContract;
use Valkyrja\Container\Generator\DataProviderFileGenerator;
use Valkyrja\Container\Provider\ServiceProvider;
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
        self::assertArrayHasKey(DataProviderFileGeneratorContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(Data::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(DataProviderFileGeneratorContract::class, ServiceProvider::provides());
        self::assertContains(Data::class, ServiceProvider::provides());
    }

    /**
     * @throws Exception
     */
    public function testPublishDataFileGenerator(): void
    {
        $this->container->setSingleton(Data::class, new Data());

        $callback = ServiceProvider::publishers()[DataProviderFileGeneratorContract::class];
        $callback($this->container);

        self::assertInstanceOf(DataProviderFileGenerator::class, $this->container->getSingleton(DataProviderFileGeneratorContract::class));
    }
}
