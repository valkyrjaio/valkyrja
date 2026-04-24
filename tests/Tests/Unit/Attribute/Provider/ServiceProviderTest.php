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
use Valkyrja\Attribute\Provider\AttributeServiceProvider;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = AttributeServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(CollectorContract::class, AttributeServiceProvider::publishers());
    }

    /**
     * @throws Exception
     */
    public function testPublishAttributes(): void
    {
        $this->container->setSingleton(ReflectorContract::class, self::createStub(ReflectorContract::class));

        $callback = AttributeServiceProvider::publishers()[CollectorContract::class];
        $callback($this->container);

        self::assertInstanceOf(Collector::class, $this->container->getSingleton(CollectorContract::class));
    }
}
