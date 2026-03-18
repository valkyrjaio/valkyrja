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
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Attribute\Collector\Contract\CollectorContract as AttributeCollectorContract;
use Valkyrja\Attribute\Provider\ServiceProvider as AttributeServiceCollector;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract as DispatchDispatcher;
use Valkyrja\Event\Collection\Collection;
use Valkyrja\Event\Collection\Contract\CollectionContract;
use Valkyrja\Event\Collector\AttributeCollector;
use Valkyrja\Event\Collector\Contract\CollectorContract;
use Valkyrja\Event\Data\Data;
use Valkyrja\Event\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Event\Dispatcher\Dispatcher;
use Valkyrja\Event\Generator\Contract\DataFileGeneratorContract;
use Valkyrja\Event\Generator\DataFileGenerator;
use Valkyrja\Event\Provider\Contract\ProviderContract;
use Valkyrja\Reflection\Provider\ServiceProvider as ReflectionServiceCollector;
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
            CollectorContract::class         => [self::class, 'publishAttributesCollector'],
            DispatcherContract::class        => [self::class, 'publishDispatcher'],
            CollectionContract::class        => [self::class, 'publishCollection'],
            DataFileGeneratorContract::class => [self::class, 'publishDataFileGenerator'],
            Data::class                      => [self::class, 'publishData'],
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
            DataFileGeneratorContract::class,
            Data::class,
        ];
    }

    /**
     * Publish the attributes service.
     */
    public static function publishAttributesCollector(ContainerContract $container): void
    {
        if (! $container->isSingleton(ReflectorContract::class)) {
            ReflectionServiceCollector::publishReflection($container);
        }

        if (! $container->isSingleton(AttributeCollectorContract::class)) {
            AttributeServiceCollector::publishAttributes($container);
        }

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
        $container->setSingleton(
            CollectionContract::class,
            $collection = new Collection()
        );

        $app = $container->getSingleton(ApplicationContract::class);

        if ($app->getDebugMode()) {
            self::publishData($container);

            return;
        }

        $data = $container->getSingleton(Data::class);

        $collection->setFromData($data);
    }

    /**
     * Publish the data file generator service.
     */
    public static function publishDataFileGenerator(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);

        /** @var non-empty-string $dataPath */
        $dataPath = $env::APP_DATA_PATH;
        /** @var non-empty-string $namespace */
        $namespace = $env::APP_DATA_NAMESPACE;
        /** @var non-empty-string $className */
        $className = $env::EVENT_DATA_CLASS_NAME
            ?? 'EventRoutingData';

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

        /** @var ProviderContract $provider */
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

        $dataGenerator = $container->getSingleton(DataFileGeneratorContract::class);
        $dataGenerator->generateFile();

        $container->setSingleton(Data::class, $collection->getData());
    }
}
