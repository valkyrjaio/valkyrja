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
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Attribute\Collector\Contract\CollectorContract;
use Valkyrja\Attribute\Provider\AttributeServiceProvider;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Event\Collection\Contract\ListenerCollectionContract;
use Valkyrja\Event\Collection\ListenerCollection;
use Valkyrja\Event\Collector\AttributeListenerCollector;
use Valkyrja\Event\Collector\Contract\ListenerCollectorContract;
use Valkyrja\Event\Data\EventData;
use Valkyrja\Event\Dispatcher\Contract\EventDispatcherContract;
use Valkyrja\Event\Dispatcher\EventDispatcher;
use Valkyrja\Event\Provider\Contract\ListenerProviderContract;
use Valkyrja\Reflection\Provider\ReflectionServiceProvider;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;

class EventServiceProvider implements ServiceProviderContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            ListenerCollectorContract::class  => [self::class, 'publishAttributesListenerCollector'],
            EventDispatcherContract::class    => [self::class, 'publishDispatcher'],
            ListenerCollectionContract::class => [self::class, 'publishListenerCollection'],
            EventData::class                  => [self::class, 'publishData'],
        ];
    }

    /**
     * Publish the attributes service.
     */
    public static function publishAttributesListenerCollector(ContainerContract $container): void
    {
        if (! $container->isSingleton(ReflectorContract::class)) {
            ReflectionServiceProvider::publishReflection($container);
        }

        if (! $container->isSingleton(CollectorContract::class)) {
            AttributeServiceProvider::publishAttributes($container);
        }

        $container->setSingleton(
            ListenerCollectorContract::class,
            new AttributeListenerCollector(
                $container->getSingleton(CollectorContract::class)
            )
        );
    }

    /**
     * Publish the dispatcher service.
     */
    public static function publishDispatcher(ContainerContract $container): void
    {
        $container->setSingleton(
            EventDispatcherContract::class,
            new EventDispatcher(
                $container->getSingleton(ListenerCollectionContract::class),
                $container,
            )
        );
    }

    /**
     * Publish the collection service.
     */
    public static function publishListenerCollection(ContainerContract $container): void
    {
        $container->setSingleton(
            ListenerCollectionContract::class,
            $collection = new ListenerCollection()
        );

        $app = $container->getSingleton(ApplicationContract::class);

        if ($app->getDebugMode()) {
            self::publishData($container);

            return;
        }

        $data = $container->getSingleton(EventData::class);

        $collection->setFromData($data);
    }

    /**
     * Publish the data service.
     */
    public static function publishData(ContainerContract $container): void
    {
        $collection  = $container->getSingleton(ListenerCollectionContract::class);
        $application = $container->getSingleton(ApplicationContract::class);

        $providers = $application->getEventProviders();

        $listenerClasses = [];
        $listeners       = [];

        foreach ($providers as $provider) {
            $listenerClasses = [
                ...$listenerClasses,
                ...$provider->getListenerClasses(),
            ];

            $listeners = [
                ...$listeners,
                ...$provider->getListeners(),
            ];
        }

        if ($listenerClasses !== []) {
            /** @var ListenerCollectorContract $listenerAttributes */
            $listenerAttributes = $container->getSingleton(ListenerCollectorContract::class);

            // Get all the annotated listeners from the list of classes
            // Iterate through the listeners
            foreach ($listenerAttributes->getListeners(...$listenerClasses) as $listener) {
                // Set the listener
                $collection->addListener($listener);
            }
        }

        foreach ($listeners as $listener) {
            $collection->addListener($listener);
        }

        $container->setSingleton(EventData::class, $collection->getData());
    }
}
