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

use Valkyrja\Application\Config\Valkyrja;
use Valkyrja\Attribute\Contract\Attributes;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactory;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandDispatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\CommandNotMatchedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandler;
use Valkyrja\Cli\Middleware\Handler\Contract\Handler;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\Cli\Routing\Attribute\Contract\Collector;
use Valkyrja\Cli\Routing\Collection\CacheableCollection;
use Valkyrja\Cli\Routing\Collection\Contract\Collection;
use Valkyrja\Cli\Routing\Contract\Router;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Dispatcher\Contract\Dispatcher2;
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
    public static function publishers(): array
    {
        return [
            Collection::class => [self::class, 'publishCollection'],
            Collector::class  => [self::class, 'publishAttributeCollector'],
            Router::class     => [self::class, 'publishRouter'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Collection::class,
            Collector::class,
            Router::class,
        ];
    }

    /**
     * Publish the attribute collector service.
     */
    public static function publishAttributeCollector(Container $container): void
    {
        $container->setSingleton(
            Collector::class,
            new \Valkyrja\Cli\Routing\Attribute\Collector(
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
        /** @var ThrowableCaughtHandler&Handler $throwableCaughtHandler */
        $throwableCaughtHandler = $container->getSingleton(ThrowableCaughtHandler::class);
        /** @var CommandMatchedHandler&Handler $commandMatchedHandler */
        $commandMatchedHandler = $container->getSingleton(CommandMatchedHandler::class);
        /** @var CommandNotMatchedHandler&Handler $commandNotMatchedHandler */
        $commandNotMatchedHandler = $container->getSingleton(CommandNotMatchedHandler::class);
        /** @var CommandDispatchedHandler&Handler $commandDispatchedHandler */
        $commandDispatchedHandler = $container->getSingleton(CommandDispatchedHandler::class);
        /** @var ExitedHandler&Handler $exitedHandler */
        $exitedHandler = $container->getSingleton(ExitedHandler::class);

        $container->setSingleton(
            Router::class,
            new \Valkyrja\Cli\Routing\Router(
                container: $container,
                dispatcher: $container->getSingleton(Dispatcher2::class),
                collection: $container->getSingleton(Collection::class),
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
        $config = $container->getSingleton(Valkyrja::class);

        $container->setSingleton(
            Collection::class,
            $collection = new CacheableCollection(
                container: $container,
                config: $config->cliRouting
            )
        );

        $collection->setup();
    }
}
