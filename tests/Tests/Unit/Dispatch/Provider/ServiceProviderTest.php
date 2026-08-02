<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
        self::assertArrayHasKey(DispatcherContract::class, new DispatchServiceProvider()->publishers());
    }

    public function testPublishDispatcher(): void
    {
        $callback = new DispatchServiceProvider()->publishers()[DispatcherContract::class];
        $callback($this->container);

        self::assertInstanceOf(Dispatcher::class, $this->container->getSingleton(DispatcherContract::class));
    }
}
