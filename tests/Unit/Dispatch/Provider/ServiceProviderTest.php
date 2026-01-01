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

namespace Valkyrja\Tests\Unit\Dispatch\Provider;

use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract as Contract;
use Valkyrja\Dispatch\Dispatcher\Dispatcher;
use Valkyrja\Dispatch\Provider\ServiceProvider;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
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
