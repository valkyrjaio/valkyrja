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

namespace Valkyrja\Tests\Unit\Http\Routing\Provider;

use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Attribute\Collector\Contract\CollectorContract;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract as HttpMessageResponseFactory;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Http\Middleware\Handler\RouteDispatchedHandler;
use Valkyrja\Http\Middleware\Handler\RouteMatchedHandler;
use Valkyrja\Http\Middleware\Handler\RouteNotMatchedHandler;
use Valkyrja\Http\Middleware\Handler\SendingResponseHandler;
use Valkyrja\Http\Middleware\Handler\TerminatedHandler;
use Valkyrja\Http\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Http\Routing\Collection\Contract\RouteCollectionContract;
use Valkyrja\Http\Routing\Collection\RouteCollection;
use Valkyrja\Http\Routing\Collector\AttributeRouteCollector;
use Valkyrja\Http\Routing\Collector\Contract\RouteCollectorContract;
use Valkyrja\Http\Routing\Data\HttpRoutingData;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Http\Routing\Dispatcher\Router;
use Valkyrja\Http\Routing\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Routing\Factory\ResponseFactory;
use Valkyrja\Http\Routing\Matcher\Contract\MatcherContract;
use Valkyrja\Http\Routing\Matcher\Matcher;
use Valkyrja\Http\Routing\Processor\Contract\ProcessorContract;
use Valkyrja\Http\Routing\Processor\Processor;
use Valkyrja\Http\Routing\Provider\HttpRoutingServiceProvider;
use Valkyrja\Http\Routing\Url\Contract\UrlContract;
use Valkyrja\Http\Routing\Url\Url;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;
use Valkyrja\Tests\Classes\Http\Routing\Provider\RouteProviderClass;

/**
 * Test the ServiceProviderTest.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = HttpRoutingServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(RouterContract::class, HttpRoutingServiceProvider::publishers());
        self::assertArrayHasKey(RouteCollectionContract::class, HttpRoutingServiceProvider::publishers());
        self::assertArrayHasKey(MatcherContract::class, HttpRoutingServiceProvider::publishers());
        self::assertArrayHasKey(UrlContract::class, HttpRoutingServiceProvider::publishers());
        self::assertArrayHasKey(RouteCollectorContract::class, HttpRoutingServiceProvider::publishers());
        self::assertArrayHasKey(ProcessorContract::class, HttpRoutingServiceProvider::publishers());
        self::assertArrayHasKey(ResponseFactoryContract::class, HttpRoutingServiceProvider::publishers());
        self::assertArrayHasKey(HttpRoutingData::class, HttpRoutingServiceProvider::publishers());
    }

    public function testPublishRouter(): void
    {
        $container = $this->container;

        $container->setSingleton(RouteDispatchedHandlerContract::class, new RouteDispatchedHandler());
        $container->setSingleton(ThrowableCaughtHandlerContract::class, new ThrowableCaughtHandler());
        $container->setSingleton(RouteMatchedHandlerContract::class, new RouteMatchedHandler());
        $container->setSingleton(RouteNotMatchedHandlerContract::class, new RouteNotMatchedHandler());
        $container->setSingleton(SendingResponseHandlerContract::class, new SendingResponseHandler());
        $container->setSingleton(TerminatedHandlerContract::class, new TerminatedHandler());
        $container->setSingleton(RouteCollectionContract::class, self::createStub(RouteCollectionContract::class));
        $container->setSingleton(DispatcherContract::class, self::createStub(DispatcherContract::class));
        $container->setSingleton(MatcherContract::class, self::createStub(MatcherContract::class));
        $container->setSingleton(HttpMessageResponseFactory::class, self::createStub(HttpMessageResponseFactory::class));

        self::assertFalse($container->has(RouterContract::class));

        $callback = HttpRoutingServiceProvider::publishers()[RouterContract::class];
        $callback($this->container);

        self::assertTrue($container->has(RouterContract::class));
        self::assertTrue($container->isSingleton(RouterContract::class));
        self::assertInstanceOf(Router::class, $container->getSingleton(RouterContract::class));
    }

    public function testPublishCollectionWithCustomDataProvided(): void
    {
        $this->container->setSingleton(ApplicationContract::class, $application = self::createStub(ApplicationContract::class));
        $this->container->setSingleton(HttpRoutingData::class, new HttpRoutingData());
        $application->method('getDebugMode')->willReturn(false);

        $callback = HttpRoutingServiceProvider::publishers()[RouteCollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(RouteCollection::class, $this->container->getSingleton(RouteCollectionContract::class));
    }

    public function testPublishCollectionWithData(): void
    {
        $container = $this->container;

        $path = '/route';
        $name = 'route';
        $data = new HttpRoutingData(
            routes: [
                $name => new Route(
                    $path,
                    $name,
                    handler: static fn (): null => null,
                ),
            ],
            paths: [
                'GET' => [$path => $name],
            ],
        );

        $container->setSingleton(ApplicationContract::class, $application = self::createStub(ApplicationContract::class));
        $container->setSingleton(RouteCollectorContract::class, self::createStub(RouteCollectorContract::class));
        $container->setSingleton(HttpRoutingData::class, $data);
        $application->method('getDebugMode')->willReturn(false);

        self::assertFalse($container->has(RouteCollectionContract::class));

        $callback = HttpRoutingServiceProvider::publishers()[RouteCollectionContract::class];
        $callback($this->container);

        self::assertTrue($container->has(RouteCollectionContract::class));
        self::assertTrue($container->isSingleton(RouteCollectionContract::class));
        self::assertInstanceOf(RouteCollection::class, $collection = $container->getSingleton(RouteCollectionContract::class));
        self::assertTrue($collection->hasPath($path, RequestMethod::ANY));
        self::assertTrue($collection->hasName($name));
    }

    public function testPublishCollectionWithoutData(): void
    {
        $this->container->register(HttpRoutingServiceProvider::class);

        $container = $this->container;

        $container->setSingleton(ApplicationContract::class, $application = $this->createMock(ApplicationContract::class));
        $container->setSingleton(RouteCollectorContract::class, $collector = $this->createMock(RouteCollectorContract::class));
        $container->setSingleton(ProcessorContract::class, $processor = $this->createMock(ProcessorContract::class));
        $application->method('getDebugMode')->willReturn(false);

        self::assertTrue($container->has(RouteCollectionContract::class));

        $route = new Route(
            path: '/',
            name: 'route',
            handler: static fn (): null => null,
        );
        $collector->expects($this->once())->method('getRoutes')->willReturn([$route]);

        $application->expects($this->once())->method('getHttpProviders')->willReturn([RouteProviderClass::class]);
        $processor->expects($this->once())->method('route')->willReturnArgument(0);

        $callback = HttpRoutingServiceProvider::publishers()[RouteCollectionContract::class];
        $callback($this->container);

        self::assertTrue($container->has(RouteCollectionContract::class));
        self::assertTrue($container->isSingleton(RouteCollectionContract::class));
        self::assertInstanceOf(RouteCollection::class, $collection = $container->getSingleton(RouteCollectionContract::class));

        self::assertNotNull($collection->getByPath('/', RequestMethod::ANY));
        self::assertNotNull($collection->getByPath('/from-provider', RequestMethod::ANY));
    }

    public function testPublishCollectionWithoutDataDebugModeTrue(): void
    {
        $this->container->register(HttpRoutingServiceProvider::class);

        $container = $this->container;

        $container->setSingleton(ApplicationContract::class, $application = $this->createMock(ApplicationContract::class));
        $container->setSingleton(RouteCollectorContract::class, $collector = $this->createMock(RouteCollectorContract::class));
        $container->setSingleton(ProcessorContract::class, $processor = $this->createMock(ProcessorContract::class));
        $application->method('getDebugMode')->willReturn(true);

        self::assertTrue($container->has(RouteCollectionContract::class));

        $route = new Route(
            path: '/',
            name: 'route',
            handler: static fn (): null => null,
        );
        $collector->expects($this->once())->method('getRoutes')->willReturn([$route]);

        $application->expects($this->once())->method('getHttpProviders')->willReturn([RouteProviderClass::class]);
        $processor->expects($this->once())->method('route')->willReturnArgument(0);

        $callback = HttpRoutingServiceProvider::publishers()[RouteCollectionContract::class];
        $callback($this->container);

        self::assertTrue($container->has(RouteCollectionContract::class));
        self::assertTrue($container->isSingleton(RouteCollectionContract::class));
        self::assertInstanceOf(RouteCollection::class, $collection = $container->getSingleton(RouteCollectionContract::class));

        self::assertNotNull($collection->getByPath('/', RequestMethod::ANY));
        self::assertNotNull($collection->getByPath('/from-provider', RequestMethod::ANY));
    }

    public function testPublishCollectionWithoutRoutes(): void
    {
        $this->container->register(HttpRoutingServiceProvider::class);

        $container = $this->container;

        $container->setSingleton(ApplicationContract::class, $application = $this->createMock(ApplicationContract::class));
        $container->setSingleton(RouteCollectorContract::class, $collector = $this->createMock(RouteCollectorContract::class));
        $container->setSingleton(ProcessorContract::class, $processor = $this->createMock(ProcessorContract::class));
        $application->method('getDebugMode')->willReturn(true);

        self::assertTrue($container->has(RouteCollectionContract::class));

        $route = new Route(
            path: '/',
            name: 'route',
            handler: static fn (): null => null,
        );
        $collector->expects($this->never())->method('getRoutes')->willReturn([$route]);

        $application->expects($this->once())->method('getHttpProviders')->willReturn([]);
        $processor->expects($this->never())->method('route')->willReturnArgument(0);

        $callback = HttpRoutingServiceProvider::publishers()[RouteCollectionContract::class];
        $callback($this->container);

        self::assertTrue($container->has(RouteCollectionContract::class));
        self::assertTrue($container->isSingleton(RouteCollectionContract::class));
        self::assertInstanceOf(RouteCollection::class, $collection = $container->getSingleton(RouteCollectionContract::class));

        self::assertFalse($collection->hasPath('/', RequestMethod::ANY));
        self::assertFalse($collection->hasPath('/from-provider', RequestMethod::ANY));
    }

    public function testPublishMatcher(): void
    {
        $container = $this->container;

        $container->setSingleton(RouteCollectionContract::class, self::createStub(RouteCollectionContract::class));

        self::assertFalse($container->has(MatcherContract::class));

        $callback = HttpRoutingServiceProvider::publishers()[MatcherContract::class];
        $callback($this->container);

        self::assertTrue($container->has(MatcherContract::class));
        self::assertTrue($container->isSingleton(MatcherContract::class));
        self::assertInstanceOf(Matcher::class, $container->getSingleton(MatcherContract::class));
    }

    public function testPublishUrl(): void
    {
        $container = $this->container;

        $container->setSingleton(RouteCollectionContract::class, self::createStub(RouteCollectionContract::class));
        $container->setSingleton(MatcherContract::class, self::createStub(MatcherContract::class));
        $container->setSingleton(ServerRequestContract::class, self::createStub(ServerRequestContract::class));

        self::assertFalse($container->has(UrlContract::class));

        $callback = HttpRoutingServiceProvider::publishers()[UrlContract::class];
        $callback($this->container);

        self::assertTrue($container->has(UrlContract::class));
        self::assertTrue($container->isSingleton(UrlContract::class));
        self::assertInstanceOf(Url::class, $container->getSingleton(UrlContract::class));
    }

    public function testPublishAttributesCollector(): void
    {
        $container = $this->container;

        $container->setSingleton(CollectorContract::class, self::createStub(CollectorContract::class));
        $container->setSingleton(ReflectorContract::class, self::createStub(ReflectorContract::class));
        $container->setSingleton(ProcessorContract::class, self::createStub(ProcessorContract::class));

        self::assertFalse($container->has(RouteCollectorContract::class));

        $callback = HttpRoutingServiceProvider::publishers()[RouteCollectorContract::class];
        $callback($this->container);

        self::assertTrue($container->has(RouteCollectorContract::class));
        self::assertTrue($container->isSingleton(RouteCollectorContract::class));
        self::assertInstanceOf(AttributeRouteCollector::class, $container->getSingleton(RouteCollectorContract::class));
    }

    public function testPublishAttributesCollectorWithoutAttributesOrReflector(): void
    {
        $container = $this->container;

        $container->setSingleton(ProcessorContract::class, self::createStub(ProcessorContract::class));

        self::assertFalse($container->has(RouteCollectorContract::class));

        $callback = HttpRoutingServiceProvider::publishers()[RouteCollectorContract::class];
        $callback($this->container);

        self::assertTrue($container->has(RouteCollectorContract::class));
        self::assertTrue($container->isSingleton(RouteCollectorContract::class));
        self::assertInstanceOf(AttributeRouteCollector::class, $container->getSingleton(RouteCollectorContract::class));
    }

    public function testPublishProcessor(): void
    {
        $container = $this->container;

        self::assertFalse($container->has(ProcessorContract::class));

        $callback = HttpRoutingServiceProvider::publishers()[ProcessorContract::class];
        $callback($this->container);

        self::assertTrue($container->has(ProcessorContract::class));
        self::assertTrue($container->isSingleton(ProcessorContract::class));
        self::assertInstanceOf(Processor::class, $container->getSingleton(ProcessorContract::class));
    }

    public function testPublishResponseFactory(): void
    {
        $container = $this->container;

        $container->setSingleton(UrlContract::class, self::createStub(UrlContract::class));
        $container->setSingleton(HttpMessageResponseFactory::class, self::createStub(HttpMessageResponseFactory::class));

        self::assertFalse($container->has(ResponseFactoryContract::class));

        $callback = HttpRoutingServiceProvider::publishers()[ResponseFactoryContract::class];
        $callback($this->container);

        self::assertTrue($container->has(ResponseFactoryContract::class));
        self::assertTrue($container->isSingleton(ResponseFactoryContract::class));
        self::assertInstanceOf(ResponseFactory::class, $container->getSingleton(ResponseFactoryContract::class));
    }
}
