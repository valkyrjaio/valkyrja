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
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Attribute\Collector\Contract\CollectorContract as AttributeCollectorContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract as DispatchDispatcher;
use Valkyrja\Event\Collection\Collection;
use Valkyrja\Event\Collection\Contract\CollectionContract;
use Valkyrja\Event\Collector\AttributeCollector;
use Valkyrja\Event\Collector\Contract\CollectorContract;
use Valkyrja\Event\Constant\AllowedClasses;
use Valkyrja\Event\Data\Data;
use Valkyrja\Event\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Event\Dispatcher\Dispatcher;
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
            CollectorContract::class  => [self::class, 'publishAttributesCollector'],
            DispatcherContract::class => [self::class, 'publishDispatcher'],
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
            CollectorContract::class,
            DispatcherContract::class,
            CollectionContract::class,
        ];
    }

    /**
     * Publish the attributes service.
     */
    public static function publishAttributesCollector(ContainerContract $container): void
    {
        $container->setSingleton(
            CollectorContract::class,
            new AttributeCollector(
                $container->getSingleton(AttributeCollectorContract::class),
                $container->getSingleton(ReflectorContract::class)
            )
        );
    }

    /**
     * Publish the dispatcher service.
     */
    public static function publishDispatcher(ContainerContract $container): void
    {
        $container->setSingleton(
            DispatcherContract::class,
            new Dispatcher(
                $container->getSingleton(CollectionContract::class),
                $container->getSingleton(DispatchDispatcher::class),
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
        $allowedClasses = $env::EVENT_COLLECTION_ALLOWED_CLASSES
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

            return;
        }

        $application = $container->getSingleton(ApplicationContract::class);

        /** @var CollectorContract $listenerAttributes */
        $listenerAttributes = $container->getSingleton(CollectorContract::class);

        // Get all the annotated listeners from the list of classes
        // Iterate through the listeners
        foreach ($listenerAttributes->getListeners(...$application->getEventListeners()) as $listener) {
            // Set the route
            $collection->addListener($listener);
        }
    }
}
