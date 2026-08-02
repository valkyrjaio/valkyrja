<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Application\Entry;

use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Application\Kernel\ChildApplication;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Tests\Fixtures\Application\Entry\WorkerHttpFixture;
use Valkyrja\Tests\Fixtures\Container\SingletonFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test WorkerHttp: parent bootstrapped once, children created per request,
 * no state leaks from child to parent or between siblings.
 */
final class WorkerHttpTest extends TestCase
{
    private ApplicationContract $app;

    private ContainerData $data;

    private ServerRequest $request;

    protected function setUp(): void
    {
        WorkerHttpFixture::reset();

        $this->app     = WorkerHttpFixture::bootstrap(new HttpConfig(dir: Directory::$basePath));
        $this->data    = $this->app->getContainer()->getData();
        $this->request = new ServerRequest();
    }

    // -----------------------------------------------------------------------
    // bootstrap() — called once before the request loop
    // -----------------------------------------------------------------------

    public function testBootstrapReturnsApplicationContract(): void
    {
        self::assertInstanceOf(ApplicationContract::class, $this->app);
    }

    public function testBootstrapCallsBootstrapParentServicesExactlyOnce(): void
    {
        self::assertSame(1, WorkerHttpFixture::$bootstrapParentServicesCallCount);
    }

    public function testBootstrapDoesNotCreateAnyChildren(): void
    {
        self::assertCount(0, WorkerHttpFixture::$childContainers);
        self::assertCount(0, WorkerHttpFixture::$childApplications);
    }

    public function testParentContainerIsRegisteredAsSingleton(): void
    {
        $container = $this->app->getContainer();

        self::assertSame($container, $container->getSingleton(ContainerContract::class));
    }

    public function testParentApplicationIsRegisteredAsSingleton(): void
    {
        $container = $this->app->getContainer();

        self::assertSame($this->app, $container->getSingleton(ApplicationContract::class));
    }

    // -----------------------------------------------------------------------
    // handle() — one child created per request
    // -----------------------------------------------------------------------

    public function testHandleCreatesOneChildContainerPerRequest(): void
    {
        WorkerHttpFixture::handle($this->app, $this->data, $this->request);
        WorkerHttpFixture::handle($this->app, $this->data, $this->request);
        WorkerHttpFixture::handle($this->app, $this->data, $this->request);

        self::assertCount(3, WorkerHttpFixture::$childContainers);
    }

    public function testHandleCreatesOneChildApplicationPerRequest(): void
    {
        WorkerHttpFixture::handle($this->app, $this->data, $this->request);
        WorkerHttpFixture::handle($this->app, $this->data, $this->request);
        WorkerHttpFixture::handle($this->app, $this->data, $this->request);

        self::assertCount(3, WorkerHttpFixture::$childApplications);
    }

    public function testEachHandleProducesADistinctChildContainer(): void
    {
        WorkerHttpFixture::handle($this->app, $this->data, $this->request);
        WorkerHttpFixture::handle($this->app, $this->data, $this->request);

        self::assertNotSame(
            WorkerHttpFixture::$childContainers[0],
            WorkerHttpFixture::$childContainers[1]
        );
    }

    public function testEachHandleProducesADistinctChildApplication(): void
    {
        WorkerHttpFixture::handle($this->app, $this->data, $this->request);
        WorkerHttpFixture::handle($this->app, $this->data, $this->request);

        self::assertNotSame(
            WorkerHttpFixture::$childApplications[0],
            WorkerHttpFixture::$childApplications[1]
        );
    }

    // -----------------------------------------------------------------------
    // Child container setup — request-scoped singletons
    // -----------------------------------------------------------------------

    public function testChildContainerHasApplicationContractSingleton(): void
    {
        WorkerHttpFixture::handle($this->app, $this->data, $this->request);

        $child = WorkerHttpFixture::$childContainers[0];

        self::assertTrue($child->isSingletonInstance(ApplicationContract::class));
    }

    public function testChildContainerHasContainerContractSingleton(): void
    {
        WorkerHttpFixture::handle($this->app, $this->data, $this->request);

        $child = WorkerHttpFixture::$childContainers[0];

        self::assertTrue($child->isSingletonInstance(ContainerContract::class));
    }

    public function testChildApplicationContractIsChildApplication(): void
    {
        WorkerHttpFixture::handle($this->app, $this->data, $this->request);

        $child    = WorkerHttpFixture::$childContainers[0];
        $childApp = $child->getSingleton(ApplicationContract::class);

        self::assertInstanceOf(ChildApplication::class, $childApp);
    }

    public function testChildContainerContractIsChildContainer(): void
    {
        WorkerHttpFixture::handle($this->app, $this->data, $this->request);

        $child = WorkerHttpFixture::$childContainers[0];

        self::assertSame($child, $child->getSingleton(ContainerContract::class));
    }

    public function testChildApplicationIsNotParentApplication(): void
    {
        WorkerHttpFixture::handle($this->app, $this->data, $this->request);

        $childApp = WorkerHttpFixture::$childApplications[0];

        self::assertNotSame($this->app, $childApp);
    }

    // -----------------------------------------------------------------------
    // No leaking — parent unchanged after handle()
    // -----------------------------------------------------------------------

    public function testParentApplicationContractUnchangedAfterHandle(): void
    {
        $parentContainer = $this->app->getContainer();
        $before          = $parentContainer->getSingleton(ApplicationContract::class);

        WorkerHttpFixture::handle($this->app, $this->data, $this->request);

        self::assertSame($before, $parentContainer->getSingleton(ApplicationContract::class));
        self::assertSame($this->app, $parentContainer->getSingleton(ApplicationContract::class));
    }

    public function testParentContainerContractUnchangedAfterHandle(): void
    {
        $parentContainer = $this->app->getContainer();
        $before          = $parentContainer->getSingleton(ContainerContract::class);

        WorkerHttpFixture::handle($this->app, $this->data, $this->request);

        self::assertSame($before, $parentContainer->getSingleton(ContainerContract::class));
        self::assertSame($parentContainer, $parentContainer->getSingleton(ContainerContract::class));
    }

    public function testChildSingletonDoesNotLeakToParent(): void
    {
        WorkerHttpFixture::handle($this->app, $this->data, $this->request);

        $child    = WorkerHttpFixture::$childContainers[0];
        $instance = new SingletonFixture();
        $child->setSingleton(SingletonFixture::class, $instance);

        self::assertFalse($this->app->getContainer()->isSingletonInstance(SingletonFixture::class));
    }

    // -----------------------------------------------------------------------
    // No leaking — child state does not reach sibling children
    // -----------------------------------------------------------------------

    public function testChildSingletonDoesNotLeakToSiblingChild(): void
    {
        WorkerHttpFixture::handle($this->app, $this->data, $this->request);
        WorkerHttpFixture::handle($this->app, $this->data, $this->request);

        $child1   = WorkerHttpFixture::$childContainers[0];
        $child2   = WorkerHttpFixture::$childContainers[1];
        $instance = new SingletonFixture();
        $child1->setSingleton(SingletonFixture::class, $instance);

        self::assertFalse($child2->isSingletonInstance(SingletonFixture::class));
    }

    public function testBootstrapParentServicesNotCalledAgainOnHandle(): void
    {
        $countAfterBootstrap = WorkerHttpFixture::$bootstrapParentServicesCallCount;

        WorkerHttpFixture::handle($this->app, $this->data, $this->request);
        WorkerHttpFixture::handle($this->app, $this->data, $this->request);

        self::assertSame($countAfterBootstrap, WorkerHttpFixture::$bootstrapParentServicesCallCount);
    }

    // -----------------------------------------------------------------------
    // run() — faux worker loop
    // -----------------------------------------------------------------------

    public function testRun(): void
    {
        WorkerHttpFixture::reset();
        WorkerHttpFixture::run(new HttpConfig(dir: Directory::$basePath), requestCount: 3);

        self::assertCount(3, WorkerHttpFixture::$childContainers);
        self::assertCount(3, WorkerHttpFixture::$childApplications);
        self::assertCount(3, WorkerHttpFixture::$requestResponses);
        self::assertSame(3, WorkerHttpFixture::$handleRouteCallCount);
        self::assertSame(3, WorkerHttpFixture::$handleRequestCallCount);
        self::assertSame(1, WorkerHttpFixture::$bootstrapParentServicesCallCount);
    }
}
