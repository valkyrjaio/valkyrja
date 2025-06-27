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

use Valkyrja\Application\Config\ValkyrjaConfig;
use Valkyrja\Attribute\Contract\Attributes;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Dispatcher\Contract\Dispatcher as DispatchDispatcher;
use Valkyrja\Event\Collection\CacheableCollection as EventCollection;
use Valkyrja\Event\Collection\Contract\Collection;
use Valkyrja\Event\Collector\AttributeCollector;
use Valkyrja\Event\Collector\Contract\Collector;
use Valkyrja\Event\Contract\Dispatcher;
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
    public static function publishers(): array
    {
        return [
            Collector::class  => [self::class, 'publishAttributesCollector'],
            Dispatcher::class => [self::class, 'publishDispatcher'],
            Collection::class => [self::class, 'publishCollection'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Collector::class,
            Dispatcher::class,
            Collection::class,
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
                $container->getSingleton(Collection::class),
                $container->getSingleton(DispatchDispatcher::class),
            )
        );
    }

    /**
     * Publish the collection service.
     */
    public static function publishCollection(Container $container): void
    {
        $config = $container->getSingleton(ValkyrjaConfig::class);

        $container->setSingleton(
            Collection::class,
            $collection = new EventCollection(
                $container->getSingleton(Container::class),
                $config->event
            )
        );

        $collection->setup();
    }
}
