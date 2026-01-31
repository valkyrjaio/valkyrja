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

namespace Valkyrja\Tests\Unit\Reflection\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Reflection\Provider\ServiceProvider;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;
use Valkyrja\Reflection\Reflector\Reflector;
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
        self::assertArrayHasKey(ReflectorContract::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(ReflectorContract::class, ServiceProvider::provides());
    }

    /**
     * @throws Exception
     */
    public function testPublishApi(): void
    {
        $this->container->setSingleton(ResponseFactoryContract::class, self::createStub(ResponseFactoryContract::class));

        $callback = ServiceProvider::publishers()[ReflectorContract::class];
        $callback($this->container);

        self::assertInstanceOf(Reflector::class, $this->container->getSingleton(ReflectorContract::class));
    }
}
