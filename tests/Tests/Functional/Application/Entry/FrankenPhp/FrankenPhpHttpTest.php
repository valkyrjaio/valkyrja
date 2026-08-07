<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Functional\Application\Entry\FrankenPhp;

use Override;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Application\Entry\FrankenPhp\FrankenPhpHttp;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Response\TextResponse;
use Valkyrja\Http\Routing\Collection\Contract\RouteCollectionContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Tests\Fixtures\Application\Entry\FrankenPhp\FrankenPhpHttpSmokeFixture;
use Valkyrja\Tests\Functional\Abstract\TestCase;

/**
 * Test the FrankenPhpHttp entry point against a fully booted application.
 */
#[RunTestsInSeparateProcesses]
final class FrankenPhpHttpTest extends TestCase
{
    private ApplicationContract $workerApp;

    /**
     * The route handler.
     */
    public static function handleRoute(
        ContainerContract $container,
        RouteContract $route
    ): TextResponse {
        return new TextResponse('FrankenPhp route');
    }

    #[Override]
    protected function setUp(): void
    {
        $this->workerApp = FrankenPhpHttp::bootstrap(new HttpConfig(dir: Directory::$basePath));

        $collection = $this->workerApp->getContainer()->getSingleton(RouteCollectionContract::class);

        $collection->add(
            new Route(
                path: '/franken',
                name: 'franken',
                handler: [self::class, 'handleRoute']
            )
        );
    }

    public function testRunLoopDispatchesRouteAndEmitsThroughSapi(): void
    {
        FrankenPhpHttpSmokeFixture::reset();

        FrankenPhpHttpSmokeFixture::$app        = $this->workerApp;
        FrankenPhpHttpSmokeFixture::$requestUri = '/franken';

        FrankenPhpHttpSmokeFixture::run(new HttpConfig());

        self::assertStringContainsString('FrankenPhp route', (string) FrankenPhpHttpSmokeFixture::$sentBody);
    }
}
