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
use Valkyrja\Event\Data\Data;
use Valkyrja\Event\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Event\Dispatcher\Dispatcher;
use Valkyrja\Event\Generator\Contract\DataFileGeneratorContract;
use Valkyrja\Event\Generator\DataFileGenerator;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;
use Valkyrja\Support\Directory\Directory;

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
        $container->setSingleton(
            CollectionContract::class,
            $collection = new Collection()
        );

        $env  = $container->getSingleton(Env::class);
        $data = null;

        /** @var bool $useCache */
        $useCache = $env::EVENT_COLLECTION_USE_CACHE
            ?? false;
        /** @var non-empty-string $cacheFilePath */
        $cacheFilePath = $env::EVENT_COLLECTION_FILE_PATH
            ?? '/events.php';
        $absoluteCacheFilePath = Directory::cachePath($cacheFilePath);

        if ($useCache && is_file(filename: $absoluteCacheFilePath)) {
            /**
             * @psalm-suppress UnresolvableInclude
             *
             * @var mixed $data The data
             */
            $data = require $absoluteCacheFilePath;
        }

        if ($data instanceof Data) {
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

        $dataGenerator = $container->getSingleton(DataFileGeneratorContract::class);
        $dataGenerator->generateFile();

        $container->setSingleton(Data::class, $collection->getData());
    }

    /**
     * Publish the data file generator service.
     */
    public static function publishDataFileGenerator(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);

        /** @var non-empty-string $cacheFilePath */
        $cacheFilePath = $env::EVENT_COLLECTION_FILE_PATH
            ?? '/events.php';
        $absoluteCacheFilePath = Directory::cachePath($cacheFilePath);

        $collection = $container->getSingleton(CollectionContract::class);

        $container->setSingleton(
            DataFileGeneratorContract::class,
            new DataFileGenerator(
                filePath: $absoluteCacheFilePath,
                data: $collection->getData(),
            )
        );
    }
}
