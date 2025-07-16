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

namespace Valkyrja\Tests\Unit\Dispatcher\Provider;

use Valkyrja\Dispatcher\Contract\Dispatcher as Contract;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Dispatcher\Provider\ServiceProvider;
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

    public function testPublishDispatcher(): void
    {
        ServiceProvider::publishDispatcher($this->container);

        self::assertInstanceOf(Dispatcher::class, $this->container->getSingleton(Contract::class));
    }
}
