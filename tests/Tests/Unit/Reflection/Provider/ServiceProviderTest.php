<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Reflection\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;
use Valkyrja\Reflection\Provider\ReflectionServiceProvider;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;
use Valkyrja\Reflection\Reflector\Reflector;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /**
     * @inheritDoc
     *
     * @var class-string<ServiceProviderContract>
     */
    protected static string $provider = ReflectionServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(ReflectorContract::class, new ReflectionServiceProvider()->publishers());
    }

    /**
     * @throws Exception
     */
    public function testPublishApi(): void
    {
        $this->container->setSingleton(ResponseFactoryContract::class, self::createStub(ResponseFactoryContract::class));

        $callback = new ReflectionServiceProvider()->publishers()[ReflectorContract::class];
        $callback($this->container);

        self::assertInstanceOf(Reflector::class, $this->container->getSingleton(ReflectorContract::class));
    }
}
