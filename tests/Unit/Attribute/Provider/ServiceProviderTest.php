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

namespace Valkyrja\Tests\Unit\Attribute\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Attribute\Collector\Collector;
use Valkyrja\Attribute\Collector\Contract\CollectorContract;
use Valkyrja\Attribute\Provider\ServiceProvider;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;
use Valkyrja\Tests\Unit\Container\Provider\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(CollectorContract::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(CollectorContract::class, ServiceProvider::provides());
    }

    /**
     * @throws Exception
     */
    public function testPublishAttributes(): void
    {
        $this->container->setSingleton(ReflectorContract::class, self::createStub(ReflectorContract::class));

        $callback = ServiceProvider::publishers()[CollectorContract::class];
        $callback($this->container);

        self::assertInstanceOf(Collector::class, $this->container->getSingleton(CollectorContract::class));
    }
}
