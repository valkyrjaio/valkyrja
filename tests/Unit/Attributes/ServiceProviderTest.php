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

namespace Valkyrja\Tests\Unit\Attributes;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Attribute\Attributes as Contract;
use Valkyrja\Attribute\Managers\Attributes;
use Valkyrja\Attribute\Providers\ServiceProvider;
use Valkyrja\Reflection\Contract\Reflector;
use Valkyrja\Tests\Unit\Container\ServiceProviderTestCase;

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
        $this->container->setSingleton(Reflector::class, $this->createMock(Reflector::class));

        ServiceProvider::publishAttributes($this->container);

        self::assertInstanceOf(Attributes::class, $this->container->getSingleton(Contract::class));
    }
}
