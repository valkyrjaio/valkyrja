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

namespace Valkyrja\Cli\Routing\Provider;

use Override;
use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Env\Env;
use Valkyrja\Attribute\Collector\Contract\CollectorContract as AttributeCollectorContract;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Routing\Collection\Collection;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Cli\Routing\Collector\AttributeCollector;
use Valkyrja\Cli\Routing\Collector\Contract\CollectorContract;
use Valkyrja\Cli\Routing\Constant\AllowedClasses;
use Valkyrja\Cli\Routing\Data\Data;
use Valkyrja\Cli\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Cli\Routing\Dispatcher\Router;
use Valkyrja\Cli\Routing\Middleware\RouteNotMatched\CheckCommandForTypoMiddleware;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            CollectorContract::class             => [self::class, 'publishAttributeCollector'],
            RouterContract::class                => [self::class, 'publishRouter'],
            CollectionContract::class            => [self::class, 'publishCollection'],
            CheckCommandForTypoMiddleware::class => [self::class, 'publishCheckCommandForTypoMiddleware'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            CollectorContract::class,
            RouterContract::class,
            CollectionContract::class,
            CheckCommandForTypoMiddleware::class,
        ];
    }

    /**
     * Publish the attribute collector service.
     */
    public static function publishAttributeCollector(ContainerContract $container): void
    {
        $container->setSingleton(
            CollectorContract::class,
            new AttributeCollector(
                attributes: $container->getSingleton(AttributeCollectorContract::class),
                reflection: $container->getSingleton(ReflectorContract::class),
            )
        );
    }

    /**
     * Publish the router service.
     */
    public static function publishRouter(ContainerContract $container): void
    {
        $throwableCaughtHandler   = $container->getSingleton(ThrowableCaughtHandlerContract::class);
        $commandMatchedHandler    = $container->getSingleton(RouteMatchedHandlerContract::class);
        $commandNotMatchedHandler = $container->getSingleton(RouteNotMatchedHandlerContract::class);
        $commandDispatchedHandler = $container->getSingleton(RouteDispatchedHandlerContract::class);
        $exitedHandler            = $container->getSingleton(ExitedHandlerContract::class);

        $container->setSingleton(
            RouterContract::class,
            new Router(
                container: $container,
                dispatcher: $container->getSingleton(DispatcherContract::class),
                collection: $container->getSingleton(CollectionContract::class),
                outputFactory: $container->getSingleton(OutputFactoryContract::class),
                throwableCaughtHandler: $throwableCaughtHandler,
                routeMatchedHandler: $commandMatchedHandler,
                routeNotMatchedHandler: $commandNotMatchedHandler,
                routeDispatchedHandler: $commandDispatchedHandler,
                exitedHandler: $exitedHandler
            )
        );
    }

    /**
     * Publish the collection service.
     */
    public static function publishCollection(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string[] $allowedClasses */
        $allowedClasses = $env::CLI_ROUTING_COLLECTION_ALLOWED_CLASSES
            ?? AllowedClasses::COLLECTION;

        $container->setSingleton(
            CollectionContract::class,
            $collection = new Collection(
                allowedClasses: $allowedClasses
            )
        );

        if ($container->isSingleton(Data::class)) {
            $data = $container->getSingleton(Data::class);

            $collection->setFromData($data);
        }

        if ($container->isSingleton(Config::class)) {
            $config = $container->getSingleton(Config::class);

            /** @var CollectorContract $collector */
            $collector   = $container->getSingleton(CollectorContract::class);
            $controllers = $config->commands;

            // Get all the attributes routes from the list of controllers
            $collection->add(
                ...$collector->getRoutes(...$controllers)
            );
        }
    }

    /**
     * Publish the check command for typo middleware service.
     */
    public static function publishCheckCommandForTypoMiddleware(ContainerContract $container): void
    {
        $container->setSingleton(
            CheckCommandForTypoMiddleware::class,
            new CheckCommandForTypoMiddleware(
                router: $container->getSingleton(RouterContract::class),
                collection: $container->getSingleton(CollectionContract::class),
            )
        );
    }
}
