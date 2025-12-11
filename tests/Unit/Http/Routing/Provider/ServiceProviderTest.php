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

use Valkyrja\Application\Config;
use Valkyrja\Attribute\Contract\Attributes as AttributesContract;
use Valkyrja\Container\Constant\ConfigValue;
use Valkyrja\Dispatcher\Contract\Dispatcher;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory as HttpMessageResponseFactory;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandler as RouteDispatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandler as RouteMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandler as RouteNotMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandler as SendingResponseHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandler as TerminatedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandler as ThrowableCaughtHandlerContract;
use Valkyrja\Http\Middleware\Handler\RouteDispatchedHandler;
use Valkyrja\Http\Middleware\Handler\RouteMatchedHandler;
use Valkyrja\Http\Middleware\Handler\RouteNotMatchedHandler;
use Valkyrja\Http\Middleware\Handler\SendingResponseHandler;
use Valkyrja\Http\Middleware\Handler\TerminatedHandler;
use Valkyrja\Http\Middleware\Handler\ThrowableCaughtHandler;
use Valkyrja\Http\Routing\Collection\Collection;
use Valkyrja\Http\Routing\Collection\Contract\Collection as CollectionContract;
use Valkyrja\Http\Routing\Collector\AttributeCollector;
use Valkyrja\Http\Routing\Collector\Contract\Collector as CollectorContract;
use Valkyrja\Http\Routing\Contract\Router as RouterContract;
use Valkyrja\Http\Routing\Data;
use Valkyrja\Http\Routing\Factory\Contract\ResponseFactory as ResponseFactoryContract;
use Valkyrja\Http\Routing\Factory\ResponseFactory;
use Valkyrja\Http\Routing\Matcher\Contract\Matcher as MatcherContract;
use Valkyrja\Http\Routing\Matcher\Matcher;
use Valkyrja\Http\Routing\Middleware\RequestStructMiddleware;
use Valkyrja\Http\Routing\Middleware\ResponseStructMiddleware;
use Valkyrja\Http\Routing\Middleware\ViewRouteNotMatchedMiddleware;
use Valkyrja\Http\Routing\Processor\Contract\Processor as ProcessorContract;
use Valkyrja\Http\Routing\Processor\Processor;
use Valkyrja\Http\Routing\Provider\ServiceProvider;
use Valkyrja\Http\Routing\Router;
use Valkyrja\Http\Routing\Url\Contract\Url as UrlContract;
use Valkyrja\Http\Routing\Url\Url;
use Valkyrja\Reflection\Contract\Reflection;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;
use Valkyrja\View\Contract\Renderer;

/**
 * Test the ServiceProviderTest.
 *
 * @author Melech Mizrachi
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    public function testServiceProviderExistsInContainer(): void
    {
        self::assertContains(ServiceProvider::class, ConfigValue::PROVIDERS);
    }

    public function testPublishersArray(): void
    {
        $publishers = ServiceProvider::publishers();

        self::assertArrayHasKey(RouterContract::class, $publishers);
        self::assertArrayHasKey(CollectionContract::class, $publishers);
        self::assertArrayHasKey(MatcherContract::class, $publishers);
        self::assertArrayHasKey(UrlContract::class, $publishers);
        self::assertArrayHasKey(CollectorContract::class, $publishers);
        self::assertArrayHasKey(ProcessorContract::class, $publishers);
        self::assertArrayHasKey(ResponseFactoryContract::class, $publishers);
        self::assertArrayHasKey(RequestStructMiddleware::class, $publishers);
        self::assertArrayHasKey(ResponseStructMiddleware::class, $publishers);
        self::assertArrayHasKey(ViewRouteNotMatchedMiddleware::class, $publishers);

        self::assertSame([ServiceProvider::class, 'publishRouter'], $publishers[RouterContract::class]);
        self::assertSame([ServiceProvider::class, 'publishCollection'], $publishers[CollectionContract::class]);
        self::assertSame([ServiceProvider::class, 'publishMatcher'], $publishers[MatcherContract::class]);
        self::assertSame([ServiceProvider::class, 'publishUrl'], $publishers[UrlContract::class]);
        self::assertSame([ServiceProvider::class, 'publishAttributesCollector'], $publishers[CollectorContract::class]);
        self::assertSame([ServiceProvider::class, 'publishProcessor'], $publishers[ProcessorContract::class]);
        self::assertSame([ServiceProvider::class, 'publishResponseFactory'], $publishers[ResponseFactoryContract::class]);
        self::assertSame([ServiceProvider::class, 'publishRequestStructMiddleware'], $publishers[RequestStructMiddleware::class]);
        self::assertSame([ServiceProvider::class, 'publishResponseStructMiddleware'], $publishers[ResponseStructMiddleware::class]);
        self::assertSame([ServiceProvider::class, 'publishViewRouteNotMatchedMiddleware'], $publishers[ViewRouteNotMatchedMiddleware::class]);
    }

    public function testProvidesArray(): void
    {
        $provides = ServiceProvider::provides();

        self::assertContains(RouterContract::class, $provides);
        self::assertContains(CollectionContract::class, $provides);
        self::assertContains(MatcherContract::class, $provides);
        self::assertContains(UrlContract::class, $provides);
        self::assertContains(CollectorContract::class, $provides);
        self::assertContains(ProcessorContract::class, $provides);
        self::assertContains(ResponseFactoryContract::class, $provides);
        self::assertContains(RequestStructMiddleware::class, $provides);
        self::assertContains(ResponseStructMiddleware::class, $provides);
        self::assertContains(ViewRouteNotMatchedMiddleware::class, $provides);
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
        $container->setSingleton(CollectionContract::class, $this->createStub(CollectionContract::class));
        $container->setSingleton(Dispatcher::class, $this->createStub(Dispatcher::class));
        $container->setSingleton(MatcherContract::class, $this->createStub(MatcherContract::class));
        $container->setSingleton(HttpMessageResponseFactory::class, $this->createStub(HttpMessageResponseFactory::class));

        self::assertFalse($container->has(RouterContract::class));

        ServiceProvider::publishRouter($container);

        self::assertTrue($container->has(RouterContract::class));
        self::assertTrue($container->isSingleton(RouterContract::class));
        self::assertInstanceOf(Router::class, $container->getSingleton(RouterContract::class));
    }

    public function testPublishCollectionData(): void
    {
        $container = $this->container;

        $container->setSingleton(CollectorContract::class, $this->createStub(CollectorContract::class));
        $container->setSingleton(Data::class, new Data());

        self::assertFalse($container->has(CollectionContract::class));

        ServiceProvider::publishCollection($container);

        self::assertTrue($container->has(CollectionContract::class));
        self::assertTrue($container->isSingleton(CollectionContract::class));
        self::assertInstanceOf(Collection::class, $container->getSingleton(CollectionContract::class));
    }

    public function testPublishCollectionConfig(): void
    {
        $container = $this->container;

        $container->setSingleton(CollectorContract::class, $collector = $this->createStub(CollectorContract::class));
        $container->setSingleton(Config::class, new Config());

        self::assertFalse($container->has(CollectionContract::class));

        $route = new Data\Route(path: '/', name: 'route');
        $collector->method('getRoutes')->willReturn([$route]);

        ServiceProvider::publishCollection($container);

        self::assertTrue($container->has(CollectionContract::class));
        self::assertTrue($container->isSingleton(CollectionContract::class));
        self::assertInstanceOf(Collection::class, $collection = $container->getSingleton(CollectionContract::class));
        self::assertNotNull($collection->get('/'));
    }

    public function testPublishMatcher(): void
    {
        $container = $this->container;

        $container->setSingleton(CollectionContract::class, $this->createStub(CollectionContract::class));

        self::assertFalse($container->has(MatcherContract::class));

        ServiceProvider::publishMatcher($container);

        self::assertTrue($container->has(MatcherContract::class));
        self::assertTrue($container->isSingleton(MatcherContract::class));
        self::assertInstanceOf(Matcher::class, $container->getSingleton(MatcherContract::class));
    }

    public function testPublishUrl(): void
    {
        $container = $this->container;

        $container->setSingleton(CollectionContract::class, $this->createStub(CollectionContract::class));
        $container->setSingleton(MatcherContract::class, $this->createStub(MatcherContract::class));
        $container->setSingleton(ServerRequest::class, $this->createStub(ServerRequest::class));

        self::assertFalse($container->has(UrlContract::class));

        ServiceProvider::publishUrl($container);

        self::assertTrue($container->has(UrlContract::class));
        self::assertTrue($container->isSingleton(UrlContract::class));
        self::assertInstanceOf(Url::class, $container->getSingleton(UrlContract::class));
    }

    public function testPublishAttributesCollector(): void
    {
        $container = $this->container;

        $container->setSingleton(AttributesContract::class, $this->createStub(AttributesContract::class));
        $container->setSingleton(Reflection::class, $this->createStub(Reflection::class));
        $container->setSingleton(ProcessorContract::class, $this->createStub(ProcessorContract::class));

        self::assertFalse($container->has(CollectorContract::class));

        ServiceProvider::publishAttributesCollector($container);

        self::assertTrue($container->has(CollectorContract::class));
        self::assertTrue($container->isSingleton(CollectorContract::class));
        self::assertInstanceOf(AttributeCollector::class, $container->getSingleton(CollectorContract::class));
    }

    public function testPublishProcessor(): void
    {
        $container = $this->container;

        self::assertFalse($container->has(ProcessorContract::class));

        ServiceProvider::publishProcessor($container);

        self::assertTrue($container->has(ProcessorContract::class));
        self::assertTrue($container->isSingleton(ProcessorContract::class));
        self::assertInstanceOf(Processor::class, $container->getSingleton(ProcessorContract::class));
    }

    public function testPublishResponseFactory(): void
    {
        $container = $this->container;

        $container->setSingleton(UrlContract::class, $this->createStub(UrlContract::class));
        $container->setSingleton(HttpMessageResponseFactory::class, $this->createStub(HttpMessageResponseFactory::class));

        self::assertFalse($container->has(ResponseFactoryContract::class));

        ServiceProvider::publishResponseFactory($container);

        self::assertTrue($container->has(ResponseFactoryContract::class));
        self::assertTrue($container->isSingleton(ResponseFactoryContract::class));
        self::assertInstanceOf(ResponseFactory::class, $container->getSingleton(ResponseFactoryContract::class));
    }

    public function testPublishRequestStructMiddleware(): void
    {
        $container = $this->container;

        self::assertFalse($container->has(RequestStructMiddleware::class));

        ServiceProvider::publishRequestStructMiddleware($container);

        self::assertTrue($container->has(RequestStructMiddleware::class));
        self::assertTrue($container->isSingleton(RequestStructMiddleware::class));
        self::assertInstanceOf(RequestStructMiddleware::class, $container->getSingleton(RequestStructMiddleware::class));
    }

    public function testPublishResponseStructMiddleware(): void
    {
        $container = $this->container;

        self::assertFalse($container->has(ResponseStructMiddleware::class));

        ServiceProvider::publishResponseStructMiddleware($container);

        self::assertTrue($container->has(ResponseStructMiddleware::class));
        self::assertTrue($container->isSingleton(ResponseStructMiddleware::class));
        self::assertInstanceOf(ResponseStructMiddleware::class, $container->getSingleton(ResponseStructMiddleware::class));
    }

    public function testPublishViewRouteNotMatchedMiddleware(): void
    {
        $container = $this->container;

        $container->setSingleton(Renderer::class, $this->createStub(Renderer::class));

        self::assertFalse($container->has(ViewRouteNotMatchedMiddleware::class));

        ServiceProvider::publishViewRouteNotMatchedMiddleware($container);

        self::assertTrue($container->has(ViewRouteNotMatchedMiddleware::class));
        self::assertTrue($container->isSingleton(ViewRouteNotMatchedMiddleware::class));
        self::assertInstanceOf(ViewRouteNotMatchedMiddleware::class, $container->getSingleton(ViewRouteNotMatchedMiddleware::class));
    }
}
