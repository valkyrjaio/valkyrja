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
use ReflectionProperty;
use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Attribute\Collector\Contract\CollectorContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Routing\Collection\Contract\RouteCollectionContract;
use Valkyrja\Cli\Routing\Collection\RouteCollection;
use Valkyrja\Cli\Routing\Collector\AttributeRouteCollector;
use Valkyrja\Cli\Routing\Collector\Contract\RouteCollectorContract;
use Valkyrja\Cli\Routing\Data\CliRoutingData;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Cli\Routing\Dispatcher\Router;
use Valkyrja\Cli\Routing\Generator\Contract\DataFileGeneratorContract;
use Valkyrja\Cli\Routing\Generator\DataFileGenerator;
use Valkyrja\Cli\Routing\Provider\CliRoutingServiceProvider;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;
use Valkyrja\Support\Generator\Enum\GenerateStatus;
use Valkyrja\Tests\Classes\Cli\Routing\Data\ConfigClass;
use Valkyrja\Tests\Classes\Cli\Routing\Provider\RouteProviderClass;
use Valkyrja\Tests\Unit\Container\Provider\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = CliRoutingServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(RouteCollectorContract::class, CliRoutingServiceProvider::publishers());
        self::assertArrayHasKey(RouterContract::class, CliRoutingServiceProvider::publishers());
        self::assertArrayHasKey(RouteCollectionContract::class, CliRoutingServiceProvider::publishers());
        self::assertArrayHasKey(DataFileGeneratorContract::class, CliRoutingServiceProvider::publishers());
        self::assertArrayHasKey(CliRoutingData::class, CliRoutingServiceProvider::publishers());
    }

    /**
     * @throws Exception
     */
    public function testPublishAttributeCollector(): void
    {
        $this->container->setSingleton(CollectorContract::class, self::createStub(CollectorContract::class));
        $this->container->setSingleton(ReflectorContract::class, self::createStub(ReflectorContract::class));

        $callback = CliRoutingServiceProvider::publishers()[RouteCollectorContract::class];
        $callback($this->container);

        self::assertInstanceOf(AttributeRouteCollector::class, $this->container->getSingleton(RouteCollectorContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishAttributeCollectorWithoutAttributesOrReflector(): void
    {
        $callback = CliRoutingServiceProvider::publishers()[RouteCollectorContract::class];
        $callback($this->container);

        self::assertInstanceOf(AttributeRouteCollector::class, $this->container->getSingleton(RouteCollectorContract::class));
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
        $this->container->setSingleton(RouteCollectionContract::class, self::createStub(RouteCollectionContract::class));
        $this->container->setSingleton(OutputFactoryContract::class, self::createStub(OutputFactoryContract::class));

        $callback = CliRoutingServiceProvider::publishers()[RouterContract::class];
        $callback($this->container);

        self::assertInstanceOf(Router::class, $this->container->getSingleton(RouterContract::class));
    }

    public function testPublishCollectionWithCustomDataProvided(): void
    {
        $this->container->setSingleton(ApplicationContract::class, $application = self::createStub(ApplicationContract::class));
        $this->container->setSingleton(CliRoutingData::class, new CliRoutingData());
        $application->method('getDebugMode')->willReturn(false);

        $callback = CliRoutingServiceProvider::publishers()[RouteCollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(RouteCollection::class, $this->container->getSingleton(RouteCollectionContract::class));
    }

    public function testPublishCollectionWithData(): void
    {
        $container = $this->container;

        $name = 'version';
        $data = new CliRoutingData(
            routes: [
                $name => new Route(
                    $name,
                    'description',
                    handler: static fn (): null => null,
                ),
            ]
        );

        $container->setSingleton(ApplicationContract::class, $application = self::createStub(ApplicationContract::class));
        $container->setSingleton(RouteCollectorContract::class, self::createStub(RouteCollectorContract::class));
        $container->setSingleton(CliRoutingData::class, $data);
        $application->method('getDebugMode')->willReturn(false);

        self::assertFalse($container->has(RouteCollectionContract::class));

        $callback = CliRoutingServiceProvider::publishers()[RouteCollectionContract::class];
        $callback($this->container);

        self::assertTrue($container->has(RouteCollectionContract::class));
        self::assertTrue($container->isSingleton(RouteCollectionContract::class));
        self::assertInstanceOf(RouteCollection::class, $collection = $container->getSingleton(RouteCollectionContract::class));
        self::assertTrue($collection->has($name));
    }

    /**
     * @throws Exception
     */
    public function testPublishCollectionWithoutData(): void
    {
        $this->container->register(CliRoutingServiceProvider::class);

        $this->container->setSingleton(ApplicationContract::class, $application = $this->createMock(ApplicationContract::class));
        $this->container->setSingleton(RouteCollectorContract::class, $collector = $this->createMock(RouteCollectorContract::class));
        $this->container->setSingleton(DataFileGeneratorContract::class, $generator = $this->createMock(DataFileGeneratorContract::class));
        $application->method('getDebugMode')->willReturn(false);

        $command = new Route(
            name: 'test',
            description: 'test',
            handler: static fn (): null => null,
        );
        $collector->expects($this->once())->method('getRoutes')->willReturn([$command]);
        $generator->expects($this->never())->method('generateFile')->willReturn(GenerateStatus::SUCCESS);

        $application->expects($this->once())->method('getCliProviders')->willReturn([RouteProviderClass::class]);

        $callback = CliRoutingServiceProvider::publishers()[RouteCollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(RouteCollection::class, $collection = $this->container->getSingleton(RouteCollectionContract::class));
        self::assertNotNull($collection->get('test'));
        self::assertNotNull($collection->get('test-provider'));
    }

    /**
     * @throws Exception
     */
    public function testPublishCollectionWithoutDataDebugModeTrue(): void
    {
        $this->container->register(CliRoutingServiceProvider::class);

        $this->container->setSingleton(ApplicationContract::class, $application = $this->createMock(ApplicationContract::class));
        $this->container->setSingleton(RouteCollectorContract::class, $collector = $this->createMock(RouteCollectorContract::class));
        $this->container->setSingleton(DataFileGeneratorContract::class, $generator = $this->createMock(DataFileGeneratorContract::class));
        $application->method('getDebugMode')->willReturn(true);

        $command = new Route(
            name: 'test',
            description: 'test',
            handler: static fn (): null => null,
        );
        $collector->expects($this->once())->method('getRoutes')->willReturn([$command]);
        $generator->expects($this->never())->method('generateFile')->willReturn(GenerateStatus::SUCCESS);

        $application->expects($this->once())->method('getCliProviders')->willReturn([RouteProviderClass::class]);

        $callback = CliRoutingServiceProvider::publishers()[RouteCollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(RouteCollection::class, $collection = $this->container->getSingleton(RouteCollectionContract::class));
        self::assertNotNull($collection->get('test'));
        self::assertNotNull($collection->get('test-provider'));
    }

    /**
     * @throws Exception
     */
    public function testPublishCollectionWithoutDataNoRoutes(): void
    {
        $this->container->register(CliRoutingServiceProvider::class);

        $this->container->setSingleton(ApplicationContract::class, $application = $this->createMock(ApplicationContract::class));
        $this->container->setSingleton(RouteCollectorContract::class, $collector = $this->createMock(RouteCollectorContract::class));
        $this->container->setSingleton(DataFileGeneratorContract::class, $generator = $this->createMock(DataFileGeneratorContract::class));
        $application->method('getDebugMode')->willReturn(true);

        $command = new Route(
            name: 'test',
            description: 'test',
            handler: static fn (): null => null,
        );
        $collector->expects($this->never())->method('getRoutes')->willReturn([$command]);
        $generator->expects($this->never())->method('generateFile')->willReturn(GenerateStatus::SUCCESS);

        $application->expects($this->once())->method('getCliProviders')->willReturn([]);

        $callback = CliRoutingServiceProvider::publishers()[RouteCollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(RouteCollection::class, $collection = $this->container->getSingleton(RouteCollectionContract::class));
        self::assertFalse($collection->has('test'));
        self::assertFalse($collection->has('test-provider'));
    }

    public function testPublishDataFileGenerator(): void
    {
        $container = $this->container;

        $container->setSingleton(RouteCollectionContract::class, self::createStub(RouteCollectionContract::class));

        self::assertFalse($container->has(RouteCollectorContract::class));

        $callback = CliRoutingServiceProvider::publishers()[DataFileGeneratorContract::class];
        $callback($this->container);

        self::assertTrue($container->has(DataFileGeneratorContract::class));
        self::assertTrue($container->isSingleton(DataFileGeneratorContract::class));
        self::assertInstanceOf(DataFileGenerator::class, $container->getSingleton(DataFileGeneratorContract::class));
    }

    public function testPublishDataFileGeneratorWithCustomConfig(): void
    {
        $container = $this->container;

        $container->setSingleton(Config::class, $config = new ConfigClass(dataClassName: 'CustomDataClassName'));
        $container->setSingleton(RouteCollectionContract::class, self::createStub(RouteCollectionContract::class));

        self::assertFalse($container->has(RouteCollectorContract::class));

        $callback = CliRoutingServiceProvider::publishers()[DataFileGeneratorContract::class];
        $callback($this->container);

        self::assertTrue($container->has(DataFileGeneratorContract::class));
        self::assertTrue($container->isSingleton(DataFileGeneratorContract::class));
        self::assertInstanceOf(DataFileGenerator::class, $generator = $container->getSingleton(DataFileGeneratorContract::class));

        $reflection = new ReflectionProperty($generator, 'className');
        $className  = $reflection->getValue($generator);

        self::assertSame($config->dataClassName, $className);
    }
}
