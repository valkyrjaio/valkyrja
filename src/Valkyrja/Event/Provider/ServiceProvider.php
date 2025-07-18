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

namespace Valkyrja\Event\Provider;

use Override;
use Valkyrja\Application\Config;
use Valkyrja\Attribute\Contract\Attributes;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Dispatcher\Contract\Dispatcher as DispatchDispatcher;
use Valkyrja\Event\Collection\Collection;
use Valkyrja\Event\Collection\Contract\Collection as CollectionContract;
use Valkyrja\Event\Collector\AttributeCollector;
use Valkyrja\Event\Collector\Contract\Collector;
use Valkyrja\Event\Contract\Dispatcher;
use Valkyrja\Event\Data;
use Valkyrja\Event\Dispatcher as EventDispatcher;
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
            Collector::class          => [self::class, 'publishAttributesCollector'],
            Dispatcher::class         => [self::class, 'publishDispatcher'],
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
            Dispatcher::class,
            CollectionContract::class,
        ];
    }

    /**
     * Publish the attributes service.
     */
    public static function publishAttributesCollector(Container $container): void
    {
        $container->setSingleton(
            Collector::class,
            new AttributeCollector(
                $container->getSingleton(Attributes::class),
                $container->getSingleton(Reflection::class)
            )
        );
    }

    /**
     * Publish the dispatcher service.
     */
    public static function publishDispatcher(Container $container): void
    {
        $container->setSingleton(
            Dispatcher::class,
            new EventDispatcher(
                $container->getSingleton(CollectionContract::class),
                $container->getSingleton(DispatchDispatcher::class),
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

            /** @var Collector $listenerAttributes */
            $listenerAttributes = $container->getSingleton(Collector::class);

            // Get all the annotated listeners from the list of classes
            // Iterate through the listeners
            foreach ($listenerAttributes->getListeners(...$config->listeners) as $listener) {
                // Set the route
                $collection->addListener($listener);
            }
        }
    }
}
