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
use Valkyrja\Attribute\Collector\Contract\Collector as Contract;
use Valkyrja\Attribute\Provider\ServiceProvider;
use Valkyrja\Reflection\Reflector\Contract\Reflector;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;

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
    public function testPublishAttributes(): void
    {
        $this->container->setSingleton(Reflector::class, self::createStub(Reflector::class));

        ServiceProvider::publishAttributes($this->container);

        self::assertInstanceOf(Collector::class, $this->container->getSingleton(Contract::class));
    }
}
