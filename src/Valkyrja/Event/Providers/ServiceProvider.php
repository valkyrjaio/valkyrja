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

namespace Valkyrja\Event\Providers;

use Valkyrja\Annotation\Filter;
use Valkyrja\Attribute\Attributes as AttributeAttributes;
use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Dispatcher\Contract\Dispatcher as DispatchDispatcher;
use Valkyrja\Event\Annotator;
use Valkyrja\Event\Attributes;
use Valkyrja\Event\Collection;
use Valkyrja\Event\Collections\CacheableCollection as EventCollection;
use Valkyrja\Event\Dispatcher;
use Valkyrja\Event\Dispatchers\Dispatcher as EventDispatcher;
use Valkyrja\Reflection\Contract\Reflection;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            Annotator::class  => [self::class, 'publishAnnotator'],
            Attributes::class => [self::class, 'publishAttributes'],
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
            Annotator::class,
            Attributes::class,
            Dispatcher::class,
            Collection::class,
        ];
    }

    /**
     * Publish the annotator service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishAnnotator(Container $container): void
    {
        $container->setSingleton(
            Annotator::class,
            new \Valkyrja\Event\Annotators\Annotator(
                $container->getSingleton(Filter::class),
                $container->getSingleton(Reflection::class)
            )
        );
    }

    /**
     * Publish the attributes service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishAttributes(Container $container): void
    {
        $container->setSingleton(
            Attributes::class,
            new Attributes\Attributes(
                $container->getSingleton(AttributeAttributes::class),
                $container->getSingleton(Reflection::class)
            )
        );
    }

    /**
     * Publish the dispatcher service.
     *
     * @param Container $container The container
     *
     * @return void
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
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishCollection(Container $container): void
    {
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Collection::class,
            $collection = new EventCollection(
                $container->getSingleton(Container::class),
                $config['event']
            )
        );

        $collection->setup();
    }
}
