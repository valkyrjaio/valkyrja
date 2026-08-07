<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Application\Entry\FrankenPhp;

use Override;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Entry\FrankenPhp\FrankenPhpHttp;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Tests\Fixtures\Application\Entry\FrankenPhp\FrankenPhpHttpFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the FrankenPhpHttp entry point.
 */
final class FrankenPhpHttpTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        FrankenPhpHttpFixture::reset();

        $container = self::createStub(ContainerContract::class);
        $container->method('getData')->willReturn(new ContainerData());

        $app = self::createStub(ApplicationContract::class);
        $app->method('getContainer')->willReturn($container);

        FrankenPhpHttpFixture::$app = $app;
    }

    public function testRunHandlesRequestsUntilRuntimeStops(): void
    {
        FrankenPhpHttpFixture::$keepRunningReturns = [true, true, false];

        FrankenPhpHttpFixture::run(new HttpConfig());

        self::assertSame(3, FrankenPhpHttpFixture::$handleCallCount);
        self::assertSame(3, FrankenPhpHttpFixture::$handleFrankenPhpRequestCallCount);
    }

    public function testRunStopsAfterMaxRequests(): void
    {
        FrankenPhpHttpFixture::$maxRequests         = 2;
        FrankenPhpHttpFixture::$keepRunningReturns  = [true, true, true, true];

        FrankenPhpHttpFixture::run(new HttpConfig());

        self::assertSame(2, FrankenPhpHttpFixture::$handleFrankenPhpRequestCallCount);
    }

    public function testRunSwallowsThrowableFromHandler(): void
    {
        FrankenPhpHttpFixture::$handleThrows        = true;
        FrankenPhpHttpFixture::$keepRunningReturns  = [false];

        FrankenPhpHttpFixture::run(new HttpConfig());

        self::assertSame(1, FrankenPhpHttpFixture::$handleCallCount);
    }

    public function testGetMaxRequestsReturnsZeroWhenUnset(): void
    {
        unset($_SERVER['MAX_REQUESTS']);

        self::assertSame(0, FrankenPhpHttp::getMaxRequests());
    }

    public function testGetMaxRequestsReadsServerValue(): void
    {
        $_SERVER['MAX_REQUESTS'] = '5';

        try {
            self::assertSame(5, FrankenPhpHttp::getMaxRequests());
        } finally {
            unset($_SERVER['MAX_REQUESTS']);
        }
    }
}
