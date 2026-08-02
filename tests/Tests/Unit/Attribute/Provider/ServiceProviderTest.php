<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
        self::assertArrayHasKey(CollectorContract::class, new AttributeServiceProvider()->publishers());
    }

    /**
     * @throws Exception
     */
    public function testPublishAttributes(): void
    {
        $this->container->setSingleton(ReflectorContract::class, self::createStub(ReflectorContract::class));

        $callback = new AttributeServiceProvider()->publishers()[CollectorContract::class];
        $callback($this->container);

        self::assertInstanceOf(Collector::class, $this->container->getSingleton(CollectorContract::class));
    }
}
