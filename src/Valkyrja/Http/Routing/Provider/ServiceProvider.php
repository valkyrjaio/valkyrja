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

use Override;
use Valkyrja\Application\Config;
use Valkyrja\Attribute\Contract\Attributes;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Dispatcher\Contract\Dispatcher;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory as HttpMessageResponseFactory;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandler;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandler;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\Http\Routing\Collection\Collection as HttpRoutingCollection;
use Valkyrja\Http\Routing\Collection\Contract\Collection;
use Valkyrja\Http\Routing\Collector\AttributeCollector;
use Valkyrja\Http\Routing\Collector\Contract\Collector;
use Valkyrja\Http\Routing\Contract\Router;
use Valkyrja\Http\Routing\Data;
use Valkyrja\Http\Routing\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Routing\Matcher\Contract\Matcher;
use Valkyrja\Http\Routing\Middleware\RequestStructMiddleware;
use Valkyrja\Http\Routing\Middleware\ResponseStructMiddleware;
use Valkyrja\Http\Routing\Middleware\ViewRouteNotMatchedMiddleware;
use Valkyrja\Http\Routing\Processor\Contract\Processor;
use Valkyrja\Http\Routing\Router as HttpRouter;
use Valkyrja\Http\Routing\Url\Contract\Url;
use Valkyrja\Reflection\Contract\Reflection;
use Valkyrja\View\Renderer\Contract\Renderer;

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
    #[Override]
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
    #[Override]
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
        $exception       = $container->getSingleton(ThrowableCaughtHandler::class);
        $routeMatched    = $container->getSingleton(RouteMatchedHandler::class);
        $routeNotMatched = $container->getSingleton(RouteNotMatchedHandler::class);
        $routeDispatched = $container->getSingleton(RouteDispatchedHandler::class);
        $sendingResponse = $container->getSingleton(SendingResponseHandler::class);
        $terminated      = $container->getSingleton(TerminatedHandler::class);

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
        $container->setSingleton(
            Collection::class,
            $collection = new HttpRoutingCollection()
        );

        if ($container->isSingleton(Data::class)) {
            $data = $container->getSingleton(Data::class);

            $collection->setFromData($data);
        }

        if ($container->isSingleton(Config::class)) {
            $config = $container->getSingleton(Config::class);

            /** @var Collector $collector */
            $collector   = $container->getSingleton(Collector::class);
            $controllers = $config->controllers;

            // Get all the attributes routes from the list of controllers
            // Iterate through the routes
            foreach ($collector->getRoutes(...$controllers) as $route) {
                // Set the route
                $collection->add($route);
            }
        }
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
                collection: $container->getSingleton(Collection::class),
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
            new AttributeCollector(
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
                renderer: $container->getSingleton(Renderer::class),
            )
        );
    }
}
