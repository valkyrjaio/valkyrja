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

namespace Valkyrja\Tests\Unit\Cli\Routing\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Attribute\Collector\Contract\CollectorContract as AttributeCollectorContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Routing\Collection\Collection;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Cli\Routing\Collector\AttributeCollector;
use Valkyrja\Cli\Routing\Collector\Contract\CollectorContract;
use Valkyrja\Cli\Routing\Data\Data;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Cli\Routing\Dispatcher\Router;
use Valkyrja\Cli\Routing\Generator\Contract\DataProviderFileGeneratorContract;
use Valkyrja\Cli\Routing\Generator\DataProviderFileGenerator;
use Valkyrja\Cli\Routing\Provider\ServiceProvider;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;
use Valkyrja\Support\Generator\Enum\GenerateStatus;
use Valkyrja\Tests\Classes\Cli\Routing\Provider\RouteProviderClass;
use Valkyrja\Tests\Unit\Container\Provider\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(CollectorContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(RouterContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(CollectionContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(DataProviderFileGeneratorContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(Data::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(CollectorContract::class, ServiceProvider::provides());
        self::assertContains(RouterContract::class, ServiceProvider::provides());
        self::assertContains(CollectionContract::class, ServiceProvider::provides());
        self::assertContains(DataProviderFileGeneratorContract::class, ServiceProvider::provides());
        self::assertContains(Data::class, ServiceProvider::provides());
    }

    /**
     * @throws Exception
     */
    public function testPublishAttributeCollector(): void
    {
        $this->container->setSingleton(AttributeCollectorContract::class, self::createStub(AttributeCollectorContract::class));
        $this->container->setSingleton(ReflectorContract::class, self::createStub(ReflectorContract::class));

        $callback = ServiceProvider::publishers()[CollectorContract::class];
        $callback($this->container);

        self::assertInstanceOf(AttributeCollector::class, $this->container->getSingleton(CollectorContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishRouter(): void
    {
        $this->container->setSingleton(ThrowableCaughtHandlerContract::class, self::createStub(ThrowableCaughtHandlerContract::class));
        $this->container->setSingleton(RouteMatchedHandlerContract::class, self::createStub(RouteMatchedHandlerContract::class));
        $this->container->setSingleton(RouteNotMatchedHandlerContract::class, self::createStub(RouteNotMatchedHandlerContract::class));
        $this->container->setSingleton(RouteDispatchedHandlerContract::class, self::createStub(RouteDispatchedHandlerContract::class));
        $this->container->setSingleton(ExitedHandlerContract::class, self::createStub(ExitedHandlerContract::class));
        $this->container->setSingleton(DispatcherContract::class, self::createStub(DispatcherContract::class));
        $this->container->setSingleton(CollectionContract::class, self::createStub(CollectionContract::class));
        $this->container->setSingleton(OutputFactoryContract::class, self::createStub(OutputFactoryContract::class));

        $callback = ServiceProvider::publishers()[RouterContract::class];
        $callback($this->container);

        self::assertInstanceOf(Router::class, $this->container->getSingleton(RouterContract::class));
    }

    public function testPublishCollectionWithCustomDataProvided(): void
    {
        $this->container->setSingleton(ApplicationContract::class, $application = self::createStub(ApplicationContract::class));
        $this->container->setSingleton(Data::class, new Data());
        $application->method('getDebugMode')->willReturn(false);

        $callback = ServiceProvider::publishers()[CollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(Collection::class, $this->container->getSingleton(CollectionContract::class));
    }

    public function testPublishCollectionWithData(): void
    {
        $container = $this->container;

        $name = 'version';
        $data = new Data(
            routes: [
                $name => new Route($name, 'description', new MethodDispatch('class', 'method')),
            ]
        );

        $container->setSingleton(ApplicationContract::class, $application = self::createStub(ApplicationContract::class));
        $container->setSingleton(CollectorContract::class, self::createStub(CollectorContract::class));
        $container->setSingleton(Data::class, $data);
        $application->method('getDebugMode')->willReturn(false);

        self::assertFalse($container->has(CollectionContract::class));

        $callback = ServiceProvider::publishers()[CollectionContract::class];
        $callback($this->container);

        self::assertTrue($container->has(CollectionContract::class));
        self::assertTrue($container->isSingleton(CollectionContract::class));
        self::assertInstanceOf(Collection::class, $collection = $container->getSingleton(CollectionContract::class));
        self::assertTrue($collection->has($name));
    }

    /**
     * @throws Exception
     */
    public function testPublishCollectionWithoutData(): void
    {
        $this->container->register(ServiceProvider::class);

        $this->container->setSingleton(ApplicationContract::class, $application = self::createStub(ApplicationContract::class));
        $this->container->setSingleton(CollectorContract::class, $collector = self::createStub(CollectorContract::class));
        $this->container->setSingleton(DataProviderFileGeneratorContract::class, $generator = self::createStub(DataProviderFileGeneratorContract::class));
        $application->method('getDebugMode')->willReturn(false);

        $command = new Route(
            name: 'test',
            description: 'test',
            dispatch: new MethodDispatch(self::class, 'dispatch')
        );
        $collector->method('getRoutes')->willReturn([$command]);
        $generator->method('generateFile')->willReturn(GenerateStatus::SUCCESS);

        $application->method('getCliProviders')->willReturn([RouteProviderClass::class]);

        $callback = ServiceProvider::publishers()[CollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(Collection::class, $collection = $this->container->getSingleton(CollectionContract::class));
        self::assertNotNull($collection->get('test'));
        self::assertNotNull($collection->get('test-provider'));
    }

    /**
     * @throws Exception
     */
    public function testPublishCollectionWithoutDataDebugModeTrue(): void
    {
        $this->container->register(ServiceProvider::class);

        $this->container->setSingleton(ApplicationContract::class, $application = self::createStub(ApplicationContract::class));
        $this->container->setSingleton(CollectorContract::class, $collector = self::createStub(CollectorContract::class));
        $this->container->setSingleton(DataProviderFileGeneratorContract::class, $generator = self::createStub(DataProviderFileGeneratorContract::class));
        $application->method('getDebugMode')->willReturn(true);

        $command = new Route(
            name: 'test',
            description: 'test',
            dispatch: new MethodDispatch(self::class, 'dispatch')
        );
        $collector->method('getRoutes')->willReturn([$command]);
        $generator->method('generateFile')->willReturn(GenerateStatus::SUCCESS);

        $application->method('getCliProviders')->willReturn([RouteProviderClass::class]);

        $callback = ServiceProvider::publishers()[CollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(Collection::class, $collection = $this->container->getSingleton(CollectionContract::class));
        self::assertNotNull($collection->get('test'));
        self::assertNotNull($collection->get('test-provider'));
    }

    public function testPublishDataFileGenerator(): void
    {
        $container = $this->container;

        $container->setSingleton(CollectionContract::class, self::createStub(CollectionContract::class));

        self::assertFalse($container->has(CollectorContract::class));

        $callback = ServiceProvider::publishers()[DataProviderFileGeneratorContract::class];
        $callback($this->container);

        self::assertTrue($container->has(DataProviderFileGeneratorContract::class));
        self::assertTrue($container->isSingleton(DataProviderFileGeneratorContract::class));
        self::assertInstanceOf(DataProviderFileGenerator::class, $container->getSingleton(DataProviderFileGeneratorContract::class));
    }
}
