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
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Dispatcher\Contract\Dispatcher;
use Valkyrja\Dispatcher\Validator\Contract\Validator;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory as HttpMessageResponseFactory;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Middleware\Handler\Contract\Handler;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandler;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\Http\Routing\Attribute\Contract\Attributes;
use Valkyrja\Http\Routing\Collection\CacheableCollection;
use Valkyrja\Http\Routing\Collection\Contract\Collection;
use Valkyrja\Http\Routing\Collector\Contract\Collector;
use Valkyrja\Http\Routing\Contract\Router;
use Valkyrja\Http\Routing\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Routing\Matcher\Contract\Matcher;
use Valkyrja\Http\Routing\Middleware\RedirectRouteMiddleware;
use Valkyrja\Http\Routing\Middleware\RequestStructMiddleware;
use Valkyrja\Http\Routing\Middleware\ResponseStructMiddleware;
use Valkyrja\Http\Routing\Middleware\SecureRouteMiddleware;
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
            Collector::class                     => [self::class, 'publishCollector'],
            Collection::class                    => [self::class, 'publishCollection'],
            Matcher::class                       => [self::class, 'publishMatcher'],
            Url::class                           => [self::class, 'publishUrl'],
            Attributes::class                    => [self::class, 'publishAttributes'],
            Processor::class                     => [self::class, 'publishProcessor'],
            ResponseFactory::class               => [self::class, 'publishResponseFactory'],
            RedirectRouteMiddleware::class       => [self::class, 'publishRedirectRouteMiddleware'],
            RequestStructMiddleware::class       => [self::class, 'publishRequestStructMiddleware'],
            ResponseStructMiddleware::class      => [self::class, 'publishResponseStructMiddleware'],
            SecureRouteMiddleware::class         => [self::class, 'publishSecureRouteMiddleware'],
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
            Collector::class,
            Collection::class,
            Matcher::class,
            Url::class,
            Attributes::class,
            Processor::class,
            ResponseFactory::class,
            RedirectRouteMiddleware::class,
            RequestStructMiddleware::class,
            ResponseStructMiddleware::class,
            SecureRouteMiddleware::class,
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
        $config = $container->getSingleton(Valkyrja::class);

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

        $routeNotMatched->add(ViewRouteNotMatchedMiddleware::class);

        $container->setSingleton(
            Router::class,
            new HttpRouter(
                collection: $container->getSingleton(Collection::class),
                container: $container,
                dispatcher: $container->getSingleton(Dispatcher::class),
                matcher: $container->getSingleton(Matcher::class),
                responseFactory: $container->getSingleton(HttpMessageResponseFactory::class),
                exceptionHandler: $exception,
                routeMatchedHandler: $routeMatched,
                routeNotMatchedHandler: $routeNotMatched,
                routeDispatchedHandler: $routeDispatched,
                sendingResponseHandler: $sendingResponse,
                terminatedHandler: $terminated,
                config: $config->httpRouting,
                debug: $config->app->debug
            )
        );
    }

    /**
     * Publish the collector service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishCollector(Container $container): void
    {
        $container->setSingleton(
            Collector::class,
            new \Valkyrja\Http\Routing\Collector\Collector(
                collection: $container->getSingleton(Collection::class),
                processor: $container->getSingleton(Processor::class),
                reflection: $container->getSingleton(Reflection::class)
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
                router: $container->getSingleton(Router::class)
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
    public static function publishAttributes(Container $container): void
    {
        $container->setSingleton(
            Attributes::class,
            new \Valkyrja\Http\Routing\Attribute\Attributes(
                attributes: $container->getSingleton(\Valkyrja\Attribute\Contract\Attributes::class),
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
            new \Valkyrja\Http\Routing\Processor\Processor(
                validator: $container->getSingleton(Validator::class)
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
     * Publish the RedirectRouteMiddleware service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRedirectRouteMiddleware(Container $container): void
    {
        $container->setSingleton(
            RedirectRouteMiddleware::class,
            new RedirectRouteMiddleware(
                $container->getSingleton(HttpMessageResponseFactory::class)
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
     * Publish the SecureRouteMiddleware service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishSecureRouteMiddleware(Container $container): void
    {
        $container->setSingleton(
            SecureRouteMiddleware::class,
            new SecureRouteMiddleware(
                $container->getSingleton(HttpMessageResponseFactory::class)
            )
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
