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

use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Attribute\Collector\Contract\CollectorContract as AttributesContract;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
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
use Valkyrja\Http\Routing\Collection\Collection;
use Valkyrja\Http\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Http\Routing\Collector\AttributeCollector;
use Valkyrja\Http\Routing\Collector\Contract\CollectorContract;
use Valkyrja\Http\Routing\Data\Data;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Http\Routing\Dispatcher\Router;
use Valkyrja\Http\Routing\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Routing\Factory\ResponseFactory;
use Valkyrja\Http\Routing\Generator\Contract\DataFileGeneratorContract;
use Valkyrja\Http\Routing\Generator\DataFileGenerator;
use Valkyrja\Http\Routing\Matcher\Contract\MatcherContract;
use Valkyrja\Http\Routing\Matcher\Matcher;
use Valkyrja\Http\Routing\Processor\Contract\ProcessorContract;
use Valkyrja\Http\Routing\Processor\Processor;
use Valkyrja\Http\Routing\Provider\ServiceProvider;
use Valkyrja\Http\Routing\Url\Contract\UrlContract;
use Valkyrja\Http\Routing\Url\Url;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;
use Valkyrja\Support\Generator\Enum\GenerateStatus;
use Valkyrja\Tests\EnvClass;
use Valkyrja\Tests\Unit\Container\Provider\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProviderTest.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(RouterContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(CollectionContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(MatcherContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(UrlContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(CollectorContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(ProcessorContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(ResponseFactoryContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(DataFileGeneratorContract::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(RouterContract::class, ServiceProvider::provides());
        self::assertContains(CollectionContract::class, ServiceProvider::provides());
        self::assertContains(MatcherContract::class, ServiceProvider::provides());
        self::assertContains(UrlContract::class, ServiceProvider::provides());
        self::assertContains(CollectorContract::class, ServiceProvider::provides());
        self::assertContains(ProcessorContract::class, ServiceProvider::provides());
        self::assertContains(ResponseFactoryContract::class, ServiceProvider::provides());
        self::assertContains(DataFileGeneratorContract::class, ServiceProvider::provides());
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
        $container->setSingleton(CollectionContract::class, self::createStub(CollectionContract::class));
        $container->setSingleton(DispatcherContract::class, self::createStub(DispatcherContract::class));
        $container->setSingleton(MatcherContract::class, self::createStub(MatcherContract::class));
        $container->setSingleton(HttpMessageResponseFactory::class, self::createStub(HttpMessageResponseFactory::class));

        self::assertFalse($container->has(RouterContract::class));

        $callback = ServiceProvider::publishers()[RouterContract::class];
        $callback($this->container);

        self::assertTrue($container->has(RouterContract::class));
        self::assertTrue($container->isSingleton(RouterContract::class));
        self::assertInstanceOf(Router::class, $container->getSingleton(RouterContract::class));
    }

    public function testPublishCollectionWithCustomDataProvided(): void
    {
        $this->container->setSingleton(Data::class, new Data());

        $callback = ServiceProvider::publishers()[CollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(Collection::class, $this->container->getSingleton(CollectionContract::class));
    }

    public function testPublishCollectionWithData(): void
    {
        $container = $this->container;

        $container->setSingleton(ApplicationContract::class, self::createStub(ApplicationContract::class));
        $container->setSingleton(CollectorContract::class, self::createStub(CollectorContract::class));
        $container->setSingleton(Env::class, new class extends Env {
            public const bool HTTP_ROUTING_COLLECTION_USE_DATA         = true;
            public const string HTTP_ROUTING_COLLECTION_DATA_FILE_PATH = 'testPublishCollectionWithData-routes.php';
        });

        $filePath  = EnvClass::APP_DIR . '/data/testPublishCollectionWithData-routes.php';
        $generator = new DataFileGenerator($filePath, new Data());
        $generator->generateFile();

        $container->setSingleton(DataFileGeneratorContract::class, $generator);

        self::assertFalse($container->has(CollectionContract::class));

        $callback = ServiceProvider::publishers()[CollectionContract::class];
        $callback($this->container);

        self::assertTrue($container->has(CollectionContract::class));
        self::assertTrue($container->isSingleton(CollectionContract::class));
        self::assertInstanceOf(Collection::class, $container->getSingleton(CollectionContract::class));

        @unlink($filePath);
    }

    public function testPublishCollectionWithoutData(): void
    {
        $container = $this->container;

        $container->setSingleton(ApplicationContract::class, self::createStub(ApplicationContract::class));
        $container->setSingleton(CollectorContract::class, $collector = self::createStub(CollectorContract::class));
        $container->setSingleton(DataFileGeneratorContract::class, $generator = self::createStub(DataFileGeneratorContract::class));

        self::assertFalse($container->has(CollectionContract::class));

        $route = new Route(
            path: '/',
            name: 'route',
            dispatch: new MethodDispatch(self::class, 'dispatch'),
        );
        $collector->method('getRoutes')->willReturn([$route]);
        $generator->method('generateFile')->willReturn(GenerateStatus::SUCCESS);

        $callback = ServiceProvider::publishers()[CollectionContract::class];
        $callback($this->container);

        self::assertTrue($container->has(CollectionContract::class));
        self::assertTrue($container->isSingleton(CollectionContract::class));
        self::assertInstanceOf(Collection::class, $collection = $container->getSingleton(CollectionContract::class));

        self::assertNotNull($collection->get('/'));
    }

    public function testPublishDataFileGenerator(): void
    {
        $container = $this->container;

        $container->setSingleton(CollectionContract::class, self::createStub(CollectionContract::class));

        self::assertFalse($container->has(CollectorContract::class));

        $callback = ServiceProvider::publishers()[DataFileGeneratorContract::class];
        $callback($this->container);

        self::assertTrue($container->has(DataFileGeneratorContract::class));
        self::assertTrue($container->isSingleton(DataFileGeneratorContract::class));
        self::assertInstanceOf(DataFileGenerator::class, $container->getSingleton(DataFileGeneratorContract::class));
    }

    public function testPublishMatcher(): void
    {
        $container = $this->container;

        $container->setSingleton(CollectionContract::class, self::createStub(CollectionContract::class));

        self::assertFalse($container->has(MatcherContract::class));

        $callback = ServiceProvider::publishers()[MatcherContract::class];
        $callback($this->container);

        self::assertTrue($container->has(MatcherContract::class));
        self::assertTrue($container->isSingleton(MatcherContract::class));
        self::assertInstanceOf(Matcher::class, $container->getSingleton(MatcherContract::class));
    }

    public function testPublishUrl(): void
    {
        $container = $this->container;

        $container->setSingleton(CollectionContract::class, self::createStub(CollectionContract::class));
        $container->setSingleton(MatcherContract::class, self::createStub(MatcherContract::class));
        $container->setSingleton(ServerRequestContract::class, self::createStub(ServerRequestContract::class));

        self::assertFalse($container->has(UrlContract::class));

        $callback = ServiceProvider::publishers()[UrlContract::class];
        $callback($this->container);

        self::assertTrue($container->has(UrlContract::class));
        self::assertTrue($container->isSingleton(UrlContract::class));
        self::assertInstanceOf(Url::class, $container->getSingleton(UrlContract::class));
    }

    public function testPublishAttributesCollector(): void
    {
        $container = $this->container;

        $container->setSingleton(AttributesContract::class, self::createStub(AttributesContract::class));
        $container->setSingleton(ReflectorContract::class, self::createStub(ReflectorContract::class));
        $container->setSingleton(ProcessorContract::class, self::createStub(ProcessorContract::class));

        self::assertFalse($container->has(CollectorContract::class));

        $callback = ServiceProvider::publishers()[CollectorContract::class];
        $callback($this->container);

        self::assertTrue($container->has(CollectorContract::class));
        self::assertTrue($container->isSingleton(CollectorContract::class));
        self::assertInstanceOf(AttributeCollector::class, $container->getSingleton(CollectorContract::class));
    }

    public function testPublishProcessor(): void
    {
        $container = $this->container;

        self::assertFalse($container->has(ProcessorContract::class));

        $callback = ServiceProvider::publishers()[ProcessorContract::class];
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

        $callback = ServiceProvider::publishers()[ResponseFactoryContract::class];
        $callback($this->container);

        self::assertTrue($container->has(ResponseFactoryContract::class));
        self::assertTrue($container->isSingleton(ResponseFactoryContract::class));
        self::assertInstanceOf(ResponseFactory::class, $container->getSingleton(ResponseFactoryContract::class));
    }
}
