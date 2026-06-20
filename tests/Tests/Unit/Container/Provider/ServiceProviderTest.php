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

use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\ContainerServiceProvider;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ContainerServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(ContainerData::class, new ContainerServiceProvider()->publishers());
    }

    public function testPublishDataRegistersProvidersAndSetsContainerData(): void
    {
        $provider = self::createStub(ServiceProviderContract::class);
        $data     = new ContainerData();

        $app = $this->createMock(ApplicationContract::class);
        $app->expects($this->once())->method('getContainerProviders')->willReturn([$provider]);

        $container = $this->createMock(ContainerContract::class);
        $container->expects($this->once())
            ->method('getSingleton')
            ->with(ApplicationContract::class)
            ->willReturn($app);
        $container->expects($this->once())->method('register')->with($provider);
        $container->expects($this->once())->method('getData')->willReturn($data);
        $container->expects($this->once())->method('setSingleton')->with(ContainerData::class, $data);

        ContainerServiceProvider::publishData($container);
    }
}
