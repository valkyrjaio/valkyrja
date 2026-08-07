<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Functional\Application\Entry\RoadRunner;

use Override;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Spiral\RoadRunner\Http\HttpWorker;
use Spiral\RoadRunner\Http\Request;
use Spiral\RoadRunner\WorkerInterface;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Application\Entry\RoadRunner\RoadRunnerHttp;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\Factory\RequestFactory;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\TextResponse;
use Valkyrja\Http\Routing\Collection\Contract\RouteCollectionContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Tests\Fixtures\Application\Entry\RoadRunner\RoadRunnerHttpSmokeFixture;
use Valkyrja\Tests\Functional\Abstract\TestCase;

/**
 * Test the RoadRunnerHttp entry point against a fully booted application.
 */
#[RunTestsInSeparateProcesses]
final class RoadRunnerHttpTest extends TestCase
{
    private ApplicationContract $workerApp;

    /**
     * The route handler.
     */
    public static function handleRoute(
        ContainerContract $container,
        RouteContract $route
    ): TextResponse {
        return new TextResponse('RoadRunner route');
    }

    #[Override]
    protected function setUp(): void
    {
        $this->workerApp = RoadRunnerHttp::bootstrap(new HttpConfig(dir: Directory::$basePath));

        $collection = $this->workerApp->getContainer()->getSingleton(RouteCollectionContract::class);

        $collection->add(
            new Route(
                path: '/roadrunner',
                name: 'roadrunner',
                handler: [self::class, 'handleRoute']
            )
        );
    }

    public function testHandleRoadRunnerRequestDispatchesAndReturnsResponse(): void
    {
        $data = $this->workerApp->getContainer()->getData();

        $request = RequestFactory::fromGlobals(
            server: [
                'REQUEST_URI'    => '/roadrunner',
                'REQUEST_METHOD' => 'GET',
            ]
        );

        $response = RoadRunnerHttp::handleRoadRunnerRequest($this->workerApp, $data, $request);

        self::assertInstanceOf(ResponseContract::class, $response);
        self::assertSame('RoadRunner route', (string) $response->getBody());
    }

    public function testRunLoopServesRequestAndRespondsToWorker(): void
    {
        RoadRunnerHttpSmokeFixture::reset();

        RoadRunnerHttpSmokeFixture::$app      = $this->workerApp;
        RoadRunnerHttpSmokeFixture::$worker   = new HttpWorker(self::createStub(WorkerInterface::class));
        RoadRunnerHttpSmokeFixture::$requests = [
            new Request(method: 'GET', uri: 'http://localhost/roadrunner'),
            null,
        ];

        RoadRunnerHttpSmokeFixture::run(new HttpConfig());

        self::assertSame(StatusCode::OK->value, RoadRunnerHttpSmokeFixture::$sentStatus);
        self::assertSame('RoadRunner route', RoadRunnerHttpSmokeFixture::$sentBody);
    }
}
