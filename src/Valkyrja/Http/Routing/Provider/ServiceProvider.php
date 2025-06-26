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

namespace Valkyrja\Http\Routing\Provider;

use Valkyrja\Application\Config\Valkyrja;
use Valkyrja\Attribute\Contract\Attributes;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Dispatcher\Contract\Dispatcher;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory as HttpMessageResponseFactory;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Middleware\Handler\Contract\Handler;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandler;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\Http\Routing\Attribute\Contract\Collector;
use Valkyrja\Http\Routing\Collection\CacheableCollection;
use Valkyrja\Http\Routing\Collection\Contract\Collection;
use Valkyrja\Http\Routing\Contract\Router;
use Valkyrja\Http\Routing\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Routing\Matcher\Contract\Matcher;
use Valkyrja\Http\Routing\Middleware\RequestStructMiddleware;
use Valkyrja\Http\Routing\Middleware\ResponseStructMiddleware;
use Valkyrja\Http\Routing\Middleware\ViewRouteNotMatchedMiddleware;
use Valkyrja\Http\Routing\Processor\Contract\Processor;
use Valkyrja\Http\Routing\Router as HttpRouter;
use Valkyrja\Http\Routing\Url\Contract\Url;
use Valkyrja\Reflection\Contract\Reflection;
use Valkyrja\View\Contract\View;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            Router::class                        => [self::class, 'publishRouter'],
            Collection::class                    => [self::class, 'publishCollection'],
            Matcher::class                       => [self::class, 'publishMatcher'],
            Url::class                           => [self::class, 'publishUrl'],
            Collector::class                     => [self::class, 'publishAttributesCollector'],
            Processor::class                     => [self::class, 'publishProcessor'],
            ResponseFactory::class               => [self::class, 'publishResponseFactory'],
            RequestStructMiddleware::class       => [self::class, 'publishRequestStructMiddleware'],
            ResponseStructMiddleware::class      => [self::class, 'publishResponseStructMiddleware'],
            ViewRouteNotMatchedMiddleware::class => [self::class, 'publishViewRouteNotMatchedMiddleware'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Router::class,
            Collection::class,
            Matcher::class,
            Url::class,
            Collector::class,
            Processor::class,
            ResponseFactory::class,
            RequestStructMiddleware::class,
            ResponseStructMiddleware::class,
            ViewRouteNotMatchedMiddleware::class,
        ];
    }

    /**
     * Publish the router service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRouter(Container $container): void
    {
        /** @var ThrowableCaughtHandler&Handler $exception */
        $exception = $container->getSingleton(ThrowableCaughtHandler::class);
        /** @var RouteMatchedHandler&Handler $routeMatched */
        $routeMatched = $container->getSingleton(RouteMatchedHandler::class);
        /** @var RouteNotMatchedHandler&Handler $routeNotMatched */
        $routeNotMatched = $container->getSingleton(RouteNotMatchedHandler::class);
        /** @var RouteDispatchedHandler&Handler $routeDispatched */
        $routeDispatched = $container->getSingleton(RouteDispatchedHandler::class);
        /** @var SendingResponseHandler&Handler $sendingResponse */
        $sendingResponse = $container->getSingleton(SendingResponseHandler::class);
        /** @var TerminatedHandler&Handler $terminated */
        $terminated = $container->getSingleton(TerminatedHandler::class);

        $container->setSingleton(
            Router::class,
            new HttpRouter(
                container: $container,
                dispatcher: $container->getSingleton(Dispatcher::class),
                matcher: $container->getSingleton(Matcher::class),
                responseFactory: $container->getSingleton(HttpMessageResponseFactory::class),
                throwableCaughtHandler: $exception,
                routeMatchedHandler: $routeMatched,
                routeNotMatchedHandler: $routeNotMatched,
                routeDispatchedHandler: $routeDispatched,
                sendingResponseHandler: $sendingResponse,
                terminatedHandler: $terminated
            )
        );
    }

    /**
     * Publish the collection service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishCollection(Container $container): void
    {
        $config = $container->getSingleton(Valkyrja::class);

        $container->setSingleton(
            Collection::class,
            $collection = new CacheableCollection(
                container: $container,
                config: $config->httpRouting
            )
        );

        $collection->setup();
    }

    /**
     * Publish the matcher service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishMatcher(Container $container): void
    {
        $container->setSingleton(
            Matcher::class,
            new \Valkyrja\Http\Routing\Matcher\Matcher(
                collection: $container->getSingleton(Collection::class)
            )
        );
    }

    /**
     * Publish the url service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishUrl(Container $container): void
    {
        $container->setSingleton(
            Url::class,
            new \Valkyrja\Http\Routing\Url\Url(
                request: $container->getSingleton(ServerRequest::class),
                collection: $container->getSingleton(Collection::class),
                matcher: $container->getSingleton(Matcher::class),
            )
        );
    }

    /**
     * Publish the route attributes service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishAttributesCollector(Container $container): void
    {
        $container->setSingleton(
            Collector::class,
            new \Valkyrja\Http\Routing\Attribute\Collector(
                attributes: $container->getSingleton(Attributes::class),
                reflection: $container->getSingleton(Reflection::class),
                processor: $container->getSingleton(Processor::class)
            )
        );
    }

    /**
     * Publish the processor service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishProcessor(Container $container): void
    {
        $container->setSingleton(
            Processor::class,
            new \Valkyrja\Http\Routing\Processor\Processor()
        );
    }

    /**
     * Publish the processor service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishResponseFactory(Container $container): void
    {
        $container->setSingleton(
            ResponseFactory::class,
            new \Valkyrja\Http\Routing\Factory\ResponseFactory(
                responseFactory: $container->getSingleton(HttpMessageResponseFactory::class),
                url: $container->getSingleton(Url::class),
            )
        );
    }

    /**
     * Publish the RequestStructMiddleware service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRequestStructMiddleware(Container $container): void
    {
        $container->setSingleton(
            RequestStructMiddleware::class,
            new RequestStructMiddleware()
        );
    }

    /**
     * Publish the ResponseStructMiddleware service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishResponseStructMiddleware(Container $container): void
    {
        $container->setSingleton(
            ResponseStructMiddleware::class,
            new ResponseStructMiddleware()
        );
    }

    /**
     * Publish the ViewRouteNotMatchedMiddleware service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishViewRouteNotMatchedMiddleware(Container $container): void
    {
        $container->setSingleton(
            ViewRouteNotMatchedMiddleware::class,
            new ViewRouteNotMatchedMiddleware(
                view: $container->getSingleton(View::class),
            )
        );
    }
}
