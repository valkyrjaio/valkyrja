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
use Valkyrja\Attribute\Contract\Attributes;
use Valkyrja\Container\Collector\AttributeCollector;
use Valkyrja\Container\Collector\Contract\Collector as AttributeCollectorContract;
use Valkyrja\Container\Provider\ServiceProvider;

/**
 * Test the ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    /**
     * @throws Exception
     */
    public function testPublishAnnotator(): void
    {
        $this->container->setSingleton(Attributes::class, $this->createMock(Attributes::class));

        ServiceProvider::publishAttributesCollector($this->container);

        self::assertInstanceOf(AttributeCollector::class, $this->container->getSingleton(AttributeCollectorContract::class));
    }
}
