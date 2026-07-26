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

namespace Valkyrja\Tests\Unit\Application\Entry\RoadRunner;

use Spiral\RoadRunner\Http\HttpWorker;
use Spiral\RoadRunner\Http\Request;
use Spiral\RoadRunner\WorkerInterface;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Entry\RoadRunner\RoadRunnerHttp;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Tests\Fixtures\Application\Entry\RoadRunner\RoadRunnerHttpFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the RoadRunnerHttp entry point.
 */
final class RoadRunnerHttpTest extends TestCase
{
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

        self::assertSame(2, RoadRunnerHttpFixture::$handleCallCount);
        self::assertSame(2, RoadRunnerHttpFixture::$getRequestFromRoadRunnerRequestCallCount);
        self::assertSame(3, RoadRunnerHttpFixture::$waitForRequestCallCount);
    }

    public function testRunStopsImmediatelyWhenFirstRequestIsNull(): void
    {
        RoadRunnerHttpFixture::$requests = [null];

        RoadRunnerHttpFixture::run(new HttpConfig());

        self::assertSame(0, RoadRunnerHttpFixture::$handleCallCount);
        self::assertSame(1, RoadRunnerHttpFixture::$waitForRequestCallCount);
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
}
