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
use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Env\Env;
use Valkyrja\Attribute\Collector\Contract\CollectorContract as AttributeCollectorContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactoryContract as HttpMessageResponseFactory;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Http\Routing\Collection\Collection;
use Valkyrja\Http\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Http\Routing\Collector\AttributeCollector;
use Valkyrja\Http\Routing\Collector\Contract\CollectorContract;
use Valkyrja\Http\Routing\Data\Data;
use Valkyrja\Http\Routing\Data\Parameter;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Http\Routing\Dispatcher\Router;
use Valkyrja\Http\Routing\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Routing\Factory\ResponseFactory;
use Valkyrja\Http\Routing\Matcher\Contract\MatcherContract;
use Valkyrja\Http\Routing\Matcher\Matcher;
use Valkyrja\Http\Routing\Middleware\RequestStructMiddleware;
use Valkyrja\Http\Routing\Middleware\ResponseStructMiddleware;
use Valkyrja\Http\Routing\Middleware\ViewRouteNotMatchedMiddleware;
use Valkyrja\Http\Routing\Processor\Contract\ProcessorContract;
use Valkyrja\Http\Routing\Processor\Processor;
use Valkyrja\Http\Routing\Url\Contract\UrlContract;
use Valkyrja\Http\Routing\Url\Url;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;
use Valkyrja\View\Renderer\Contract\RendererContract;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            RouterContract::class                => [self::class, 'publishRouter'],
            CollectionContract::class            => [self::class, 'publishCollection'],
            MatcherContract::class               => [self::class, 'publishMatcher'],
            UrlContract::class                   => [self::class, 'publishUrl'],
            CollectorContract::class             => [self::class, 'publishAttributesCollector'],
            ProcessorContract::class             => [self::class, 'publishProcessor'],
            ResponseFactoryContract::class       => [self::class, 'publishResponseFactory'],
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
            RouterContract::class,
            CollectionContract::class,
            MatcherContract::class,
            UrlContract::class,
            CollectorContract::class,
            ProcessorContract::class,
            ResponseFactoryContract::class,
            RequestStructMiddleware::class,
            ResponseStructMiddleware::class,
            ViewRouteNotMatchedMiddleware::class,
        ];
    }

    /**
     * Publish the router service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishRouter(ContainerContract $container): void
    {
        $exception       = $container->getSingleton(ThrowableCaughtHandlerContract::class);
        $routeMatched    = $container->getSingleton(RouteMatchedHandlerContract::class);
        $routeNotMatched = $container->getSingleton(RouteNotMatchedHandlerContract::class);
        $routeDispatched = $container->getSingleton(RouteDispatchedHandlerContract::class);
        $sendingResponse = $container->getSingleton(SendingResponseHandlerContract::class);
        $terminated      = $container->getSingleton(TerminatedHandlerContract::class);

        $container->setSingleton(
            RouterContract::class,
            new Router(
                container: $container,
                dispatcher: $container->getSingleton(DispatcherContract::class),
                matcher: $container->getSingleton(MatcherContract::class),
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
     * @param ContainerContract $container The container
     */
    public static function publishCollection(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string[] $allowedClasses */
        $allowedClasses = $env::HTTP_ROUTING_COLLECTION_ALLOWED_CLASSES
            ?? [
                Route::class,
                Parameter::class,
                MethodDispatch::class,
                RequestMethod::class,
            ];

        $container->setSingleton(
            CollectionContract::class,
            $collection = new Collection(allowedClasses: $allowedClasses)
        );

        if ($container->isSingleton(Data::class)) {
            $data = $container->getSingleton(Data::class);

            $collection->setFromData($data);
        }

        if ($container->isSingleton(Config::class)) {
            $config = $container->getSingleton(Config::class);

            /** @var CollectorContract $collector */
            $collector   = $container->getSingleton(CollectorContract::class);
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
     * @param ContainerContract $container The container
     */
    public static function publishMatcher(ContainerContract $container): void
    {
        $container->setSingleton(
            MatcherContract::class,
            new Matcher(
                collection: $container->getSingleton(CollectionContract::class)
            )
        );
    }

    /**
     * Publish the url service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishUrl(ContainerContract $container): void
    {
        $container->setSingleton(
            UrlContract::class,
            new Url(
                collection: $container->getSingleton(CollectionContract::class),
            )
        );
    }

    /**
     * Publish the route attributes service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishAttributesCollector(ContainerContract $container): void
    {
        $container->setSingleton(
            CollectorContract::class,
            new AttributeCollector(
                attributes: $container->getSingleton(AttributeCollectorContract::class),
                reflection: $container->getSingleton(ReflectorContract::class),
                processor: $container->getSingleton(ProcessorContract::class)
            )
        );
    }

    /**
     * Publish the processor service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishProcessor(ContainerContract $container): void
    {
        $container->setSingleton(
            ProcessorContract::class,
            new Processor()
        );
    }

    /**
     * Publish the processor service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishResponseFactory(ContainerContract $container): void
    {
        $container->setSingleton(
            ResponseFactoryContract::class,
            new ResponseFactory(
                responseFactory: $container->getSingleton(HttpMessageResponseFactory::class),
                url: $container->getSingleton(UrlContract::class),
            )
        );
    }

    /**
     * Publish the RequestStructMiddleware service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishRequestStructMiddleware(ContainerContract $container): void
    {
        $container->setSingleton(
            RequestStructMiddleware::class,
            new RequestStructMiddleware()
        );
    }

    /**
     * Publish the ResponseStructMiddleware service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishResponseStructMiddleware(ContainerContract $container): void
    {
        $container->setSingleton(
            ResponseStructMiddleware::class,
            new ResponseStructMiddleware()
        );
    }

    /**
     * Publish the ViewRouteNotMatchedMiddleware service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishViewRouteNotMatchedMiddleware(ContainerContract $container): void
    {
        $container->setSingleton(
            ViewRouteNotMatchedMiddleware::class,
            new ViewRouteNotMatchedMiddleware(
                renderer: $container->getSingleton(RendererContract::class),
            )
        );
    }
}
