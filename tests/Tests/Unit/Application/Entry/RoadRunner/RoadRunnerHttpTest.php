<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Application\Entry\RoadRunner;

use Override;
use Spiral\RoadRunner\Http\HttpWorker;
use Spiral\RoadRunner\Http\Request;
use Spiral\RoadRunner\WorkerInterface;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Entry\RoadRunner\RoadRunnerHttp;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\TextResponse;
use Valkyrja\Tests\Fixtures\Application\Entry\RoadRunner\RoadRunnerHttpFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the RoadRunnerHttp entry point.
 */
final class RoadRunnerHttpTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        RoadRunnerHttpFixture::reset();

        $container = self::createStub(ContainerContract::class);
        $container->method('getData')->willReturn(new ContainerData());

        $app = self::createStub(ApplicationContract::class);
        $app->method('getContainer')->willReturn($container);

        RoadRunnerHttpFixture::$app    = $app;
        RoadRunnerHttpFixture::$worker = new HttpWorker(self::createStub(WorkerInterface::class));
    }

    public function testRunHandlesEachRequestUntilNull(): void
    {
        RoadRunnerHttpFixture::$requests = [new Request(), new Request(), null];

        RoadRunnerHttpFixture::run(new HttpConfig());

        self::assertSame(2, RoadRunnerHttpFixture::$handleRoadRunnerRequestCallCount);
        self::assertSame(2, RoadRunnerHttpFixture::$getRequestFromRoadRunnerRequestCallCount);
        self::assertSame(3, RoadRunnerHttpFixture::$waitForRequestCallCount);
        self::assertSame(StatusCode::OK->value, RoadRunnerHttpFixture::$sentStatus);
    }

    public function testRunStopsImmediatelyWhenFirstRequestIsNull(): void
    {
        RoadRunnerHttpFixture::$requests = [null];

        RoadRunnerHttpFixture::run(new HttpConfig());

        self::assertSame(0, RoadRunnerHttpFixture::$handleRoadRunnerRequestCallCount);
        self::assertSame(1, RoadRunnerHttpFixture::$waitForRequestCallCount);
        self::assertNull(RoadRunnerHttpFixture::$sentStatus);
    }

    public function testGetRequestFromRoadRunnerRequestBuildsServerRequest(): void
    {
        $roadRunnerRequest = new Request(
            cookies: ['session' => 'abc'],
            query: ['foo' => 'bar'],
            body: 'payload',
        );

        $request = RoadRunnerHttp::getRequestFromRoadRunnerRequest($roadRunnerRequest);

        self::assertInstanceOf(ServerRequestContract::class, $request);
        self::assertSame('payload', (string) $request->getBody());
    }

    public function testRespondToWorkerWritesStatusHeadersAndBody(): void
    {
        $response = new TextResponse('RR body', StatusCode::ACCEPTED);

        RoadRunnerHttpFixture::respondToWorker(RoadRunnerHttpFixture::$worker, $response);

        self::assertSame(StatusCode::ACCEPTED->value, RoadRunnerHttpFixture::$sentStatus);
        self::assertSame('RR body', RoadRunnerHttpFixture::$sentBody);
        self::assertArrayHasKey('Content-Type', RoadRunnerHttpFixture::$sentHeaders);
        self::assertSame(
            [$response->getHeaders()->getHeaderLine('Content-Type')],
            RoadRunnerHttpFixture::$sentHeaders['Content-Type']
        );
    }
}
