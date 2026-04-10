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

namespace Valkyrja\Tests\Unit\Application\Entry;

use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Application\Kernel\ChildApplication;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Request\ServerRequest;
use Valkyrja\Tests\Classes\Application\Entry\WorkerHttpClass;
use Valkyrja\Tests\Classes\Container\SingletonClass;
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
        WorkerHttpClass::reset();

        $this->app     = WorkerHttpClass::bootstrap(new HttpConfig(dir: Directory::$basePath));
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
        self::assertSame(1, WorkerHttpClass::$bootstrapParentServicesCallCount);
    }

    public function testBootstrapDoesNotCreateAnyChildren(): void
    {
        self::assertCount(0, WorkerHttpClass::$childContainers);
        self::assertCount(0, WorkerHttpClass::$childApplications);
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
        WorkerHttpClass::handle($this->app, $this->data, $this->request);
        WorkerHttpClass::handle($this->app, $this->data, $this->request);
        WorkerHttpClass::handle($this->app, $this->data, $this->request);

        self::assertCount(3, WorkerHttpClass::$childContainers);
    }

    public function testHandleCreatesOneChildApplicationPerRequest(): void
    {
        WorkerHttpClass::handle($this->app, $this->data, $this->request);
        WorkerHttpClass::handle($this->app, $this->data, $this->request);
        WorkerHttpClass::handle($this->app, $this->data, $this->request);

        self::assertCount(3, WorkerHttpClass::$childApplications);
    }

    public function testEachHandleProducesADistinctChildContainer(): void
    {
        WorkerHttpClass::handle($this->app, $this->data, $this->request);
        WorkerHttpClass::handle($this->app, $this->data, $this->request);

        self::assertNotSame(
            WorkerHttpClass::$childContainers[0],
            WorkerHttpClass::$childContainers[1]
        );
    }

    public function testEachHandleProducesADistinctChildApplication(): void
    {
        WorkerHttpClass::handle($this->app, $this->data, $this->request);
        WorkerHttpClass::handle($this->app, $this->data, $this->request);

        self::assertNotSame(
            WorkerHttpClass::$childApplications[0],
            WorkerHttpClass::$childApplications[1]
        );
    }

    // -----------------------------------------------------------------------
    // Child container setup — request-scoped singletons
    // -----------------------------------------------------------------------

    public function testChildContainerHasApplicationContractSingleton(): void
    {
        WorkerHttpClass::handle($this->app, $this->data, $this->request);

        $child = WorkerHttpClass::$childContainers[0];

        self::assertTrue($child->isSingletonInstance(ApplicationContract::class));
    }

    public function testChildContainerHasContainerContractSingleton(): void
    {
        WorkerHttpClass::handle($this->app, $this->data, $this->request);

        $child = WorkerHttpClass::$childContainers[0];

        self::assertTrue($child->isSingletonInstance(ContainerContract::class));
    }

    public function testChildApplicationContractIsChildApplication(): void
    {
        WorkerHttpClass::handle($this->app, $this->data, $this->request);

        $child    = WorkerHttpClass::$childContainers[0];
        $childApp = $child->getSingleton(ApplicationContract::class);

        self::assertInstanceOf(ChildApplication::class, $childApp);
    }

    public function testChildContainerContractIsChildContainer(): void
    {
        WorkerHttpClass::handle($this->app, $this->data, $this->request);

        $child = WorkerHttpClass::$childContainers[0];

        self::assertSame($child, $child->getSingleton(ContainerContract::class));
    }

    public function testChildApplicationIsNotParentApplication(): void
    {
        WorkerHttpClass::handle($this->app, $this->data, $this->request);

        $childApp = WorkerHttpClass::$childApplications[0];

        self::assertNotSame($this->app, $childApp);
    }

    // -----------------------------------------------------------------------
    // No leaking — parent unchanged after handle()
    // -----------------------------------------------------------------------

    public function testParentApplicationContractUnchangedAfterHandle(): void
    {
        $parentContainer = $this->app->getContainer();
        $before          = $parentContainer->getSingleton(ApplicationContract::class);

        WorkerHttpClass::handle($this->app, $this->data, $this->request);

        self::assertSame($before, $parentContainer->getSingleton(ApplicationContract::class));
        self::assertSame($this->app, $parentContainer->getSingleton(ApplicationContract::class));
    }

    public function testParentContainerContractUnchangedAfterHandle(): void
    {
        $parentContainer = $this->app->getContainer();
        $before          = $parentContainer->getSingleton(ContainerContract::class);

        WorkerHttpClass::handle($this->app, $this->data, $this->request);

        self::assertSame($before, $parentContainer->getSingleton(ContainerContract::class));
        self::assertSame($parentContainer, $parentContainer->getSingleton(ContainerContract::class));
    }

    public function testChildSingletonDoesNotLeakToParent(): void
    {
        WorkerHttpClass::handle($this->app, $this->data, $this->request);

        $child    = WorkerHttpClass::$childContainers[0];
        $instance = new SingletonClass();
        $child->setSingleton(SingletonClass::class, $instance);

        self::assertFalse($this->app->getContainer()->isSingletonInstance(SingletonClass::class));
    }

    // -----------------------------------------------------------------------
    // No leaking — child state does not reach sibling children
    // -----------------------------------------------------------------------

    public function testChildSingletonDoesNotLeakToSiblingChild(): void
    {
        WorkerHttpClass::handle($this->app, $this->data, $this->request);
        WorkerHttpClass::handle($this->app, $this->data, $this->request);

        $child1   = WorkerHttpClass::$childContainers[0];
        $child2   = WorkerHttpClass::$childContainers[1];
        $instance = new SingletonClass();
        $child1->setSingleton(SingletonClass::class, $instance);

        self::assertFalse($child2->isSingletonInstance(SingletonClass::class));
    }

    public function testBootstrapParentServicesNotCalledAgainOnHandle(): void
    {
        $countAfterBootstrap = WorkerHttpClass::$bootstrapParentServicesCallCount;

        WorkerHttpClass::handle($this->app, $this->data, $this->request);
        WorkerHttpClass::handle($this->app, $this->data, $this->request);

        self::assertSame($countAfterBootstrap, WorkerHttpClass::$bootstrapParentServicesCallCount);
    }

    // -----------------------------------------------------------------------
    // run() — faux worker loop
    // -----------------------------------------------------------------------

    public function testRun(): void
    {
        WorkerHttpClass::reset();
        WorkerHttpClass::run(new HttpConfig(dir: Directory::$basePath), requestCount: 3);

        self::assertCount(3, WorkerHttpClass::$childContainers);
        self::assertCount(3, WorkerHttpClass::$childApplications);
        self::assertCount(3, WorkerHttpClass::$requestResponses);
        self::assertSame(3, WorkerHttpClass::$handleRouteCallCount);
        self::assertSame(3, WorkerHttpClass::$handleRequestCallCount);
        self::assertSame(1, WorkerHttpClass::$bootstrapParentServicesCallCount);
    }
}
