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
use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Attribute\Collector\Contract\CollectorContract as AttributeCollectorContract;
use Valkyrja\Attribute\Provider\AttributeServiceProvider;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Event\Collection\Collection;
use Valkyrja\Event\Collection\Contract\CollectionContract;
use Valkyrja\Event\Collector\AttributeCollector;
use Valkyrja\Event\Collector\Contract\CollectorContract;
use Valkyrja\Event\Data\EventData;
use Valkyrja\Event\Dispatcher\Contract\EventDispatcherContract;
use Valkyrja\Event\Dispatcher\EventDispatcher;
use Valkyrja\Event\Generator\Contract\DataFileGeneratorContract;
use Valkyrja\Event\Generator\DataFileGenerator;
use Valkyrja\Event\Provider\Contract\ListenerProviderContract;
use Valkyrja\Reflection\Provider\ReflectionServiceProvider;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;

class EventServiceProvider implements ServiceProviderContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            CollectorContract::class         => [self::class, 'publishAttributesCollector'],
            EventDispatcherContract::class   => [self::class, 'publishDispatcher'],
            CollectionContract::class        => [self::class, 'publishCollection'],
            DataFileGeneratorContract::class => [self::class, 'publishDataFileGenerator'],
            EventData::class                 => [self::class, 'publishData'],
        ];
    }

    /**
     * Publish the attributes service.
     */
    public static function publishAttributesCollector(ContainerContract $container): void
    {
        if (! $container->isSingleton(ReflectorContract::class)) {
            ReflectionServiceProvider::publishReflection($container);
        }

        if (! $container->isSingleton(AttributeCollectorContract::class)) {
            AttributeServiceProvider::publishAttributes($container);
        }

        $container->setSingleton(
            CollectorContract::class,
            new AttributeCollector(
                $container->getSingleton(AttributeCollectorContract::class)
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
                $container->getSingleton(CollectionContract::class),
                $container,
            )
        );
    }

    /**
     * Publish the collection service.
     */
    public static function publishCollection(ContainerContract $container): void
    {
        $container->setSingleton(
            CollectionContract::class,
            $collection = new Collection()
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
     * Publish the data file generator service.
     */
    public static function publishDataFileGenerator(ContainerContract $container): void
    {
        $env    = $container->getSingleton(Env::class);
        $config = $container->getSingleton(Config::class);

        $dataPath  = $config->dataPath;
        $namespace = $config->dataNamespace;
        /** @var non-empty-string $className */
        $className = $env::EVENT_DATA_CLASS_NAME
            ?? 'AppEventData';

        $directory = Directory::srcPath($dataPath);

        $collection = $container->getSingleton(CollectionContract::class);

        $container->setSingleton(
            DataFileGeneratorContract::class,
            new DataFileGenerator(
                directory: $directory,
                data: $collection->getData(),
                namespace: $namespace,
                className: $className,
            )
        );
    }

    /**
     * Publish the data service.
     */
    public static function publishData(ContainerContract $container): void
    {
        $collection  = $container->getSingleton(CollectionContract::class);
        $application = $container->getSingleton(ApplicationContract::class);

        $providers = $application->getEventProviders();

        $listenerClasses = [];
        $listeners       = [];

        /** @var ListenerProviderContract $provider */
        foreach ($providers as $provider) {
            $listenerClasses = [
                ...$listenerClasses,
                ...$provider::getListenerClasses(),
            ];

            $listeners = [
                ...$listeners,
                ...$provider::getListeners(),
            ];
        }

        if ($listenerClasses !== []) {
            /** @var CollectorContract $listenerAttributes */
            $listenerAttributes = $container->getSingleton(CollectorContract::class);

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
