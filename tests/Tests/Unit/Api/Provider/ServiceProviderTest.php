<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Api\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Api\Manager\Api;
use Valkyrja\Api\Manager\Contract\ApiContract;
use Valkyrja\Api\Provider\ApiServiceProvider;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ApiServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(ApiContract::class, new ApiServiceProvider()->publishers());
    }

    /**
     * @throws Exception
     */
    public function testPublishApi(): void
    {
        $this->container->setSingleton(ResponseFactoryContract::class, self::createStub(ResponseFactoryContract::class));

        $callback = new ApiServiceProvider()->publishers()[ApiContract::class];
        $callback($this->container);

        self::assertInstanceOf(Api::class, $this->container->getSingleton(ApiContract::class));
    }
}
