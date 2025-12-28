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
use Valkyrja\Application\Config;
use Valkyrja\Attribute\Contract\Attributes;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactory;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandDispatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandNotMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\Cli\Routing\Collection\Collection;
use Valkyrja\Cli\Routing\Collection\Contract\Collection as CollectionContract;
use Valkyrja\Cli\Routing\Collector\AttributeCollector;
use Valkyrja\Cli\Routing\Collector\Contract\Collector;
use Valkyrja\Cli\Routing\Contract\Router as RouterContract;
use Valkyrja\Cli\Routing\Data;
use Valkyrja\Cli\Routing\Router;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Dispatcher\Contract\Dispatcher;
use Valkyrja\Reflection\Contract\Reflection;

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
            Collector::class          => [self::class, 'publishAttributeCollector'],
            RouterContract::class     => [self::class, 'publishRouter'],
            CollectionContract::class => [self::class, 'publishCollection'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            Collector::class,
            RouterContract::class,
            CollectionContract::class,
        ];
    }

    /**
     * Publish the attribute collector service.
     */
    public static function publishAttributeCollector(Container $container): void
    {
        $container->setSingleton(
            Collector::class,
            new AttributeCollector(
                attributes: $container->getSingleton(Attributes::class),
                reflection: $container->getSingleton(Reflection::class),
            )
        );
    }

    /**
     * Publish the router service.
     */
    public static function publishRouter(Container $container): void
    {
        $throwableCaughtHandler   = $container->getSingleton(ThrowableCaughtHandler::class);
        $commandMatchedHandler    = $container->getSingleton(CommandMatchedHandler::class);
        $commandNotMatchedHandler = $container->getSingleton(CommandNotMatchedHandler::class);
        $commandDispatchedHandler = $container->getSingleton(CommandDispatchedHandler::class);
        $exitedHandler            = $container->getSingleton(ExitedHandler::class);

        $container->setSingleton(
            RouterContract::class,
            new Router(
                container: $container,
                dispatcher: $container->getSingleton(Dispatcher::class),
                collection: $container->getSingleton(CollectionContract::class),
                outputFactory: $container->getSingleton(OutputFactory::class),
                throwableCaughtHandler: $throwableCaughtHandler,
                commandMatchedHandler: $commandMatchedHandler,
                commandNotMatchedHandler: $commandNotMatchedHandler,
                commandDispatchedHandler: $commandDispatchedHandler,
                exitedHandler: $exitedHandler
            )
        );
    }

    /**
     * Publish the collection service.
     */
    public static function publishCollection(Container $container): void
    {
        $container->setSingleton(
            CollectionContract::class,
            $collection = new Collection()
        );

        if ($container->isSingleton(Data::class)) {
            $data = $container->getSingleton(Data::class);

            $collection->setFromData($data);
        }

        if ($container->isSingleton(Config::class)) {
            $config = $container->getSingleton(Config::class);

            /** @var Collector $collector */
            $collector   = $container->getSingleton(Collector::class);
            $controllers = $config->commands;

            // Get all the attributes routes from the list of controllers
            $collection->add(
                ...$collector->getRoutes(...$controllers)
            );
        }
    }
}
