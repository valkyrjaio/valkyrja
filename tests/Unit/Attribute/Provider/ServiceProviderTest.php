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
use Valkyrja\Attribute\Attributes;
use Valkyrja\Attribute\Contract\Attributes as Contract;
use Valkyrja\Attribute\Provider\ServiceProvider;
use Valkyrja\Reflection\Contract\Reflection;
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
        $this->container->setSingleton(Reflection::class, $this->createMock(Reflection::class));

        ServiceProvider::publishAttributes($this->container);

        self::assertInstanceOf(Attributes::class, $this->container->getSingleton(Contract::class));
    }
}
