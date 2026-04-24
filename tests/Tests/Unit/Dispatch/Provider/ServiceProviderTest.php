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

use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Dispatch\Dispatcher\Dispatcher;
use Valkyrja\Dispatch\Provider\DispatchServiceProvider;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = DispatchServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(DispatcherContract::class, DispatchServiceProvider::publishers());
    }

    public function testPublishDispatcher(): void
    {
        $callback = DispatchServiceProvider::publishers()[DispatcherContract::class];
        $callback($this->container);

        self::assertInstanceOf(Dispatcher::class, $this->container->getSingleton(DispatcherContract::class));
    }
}
