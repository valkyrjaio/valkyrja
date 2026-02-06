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
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Attribute\Collector\Contract\CollectorContract as AttributeCollectorContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Routing\Collection\Collection;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Cli\Routing\Collector\AttributeCollector;
use Valkyrja\Cli\Routing\Collector\Contract\CollectorContract;
use Valkyrja\Cli\Routing\Data\Data;
use Valkyrja\Cli\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Cli\Routing\Dispatcher\Router;
use Valkyrja\Cli\Routing\Generator\Contract\DataFileGeneratorContract;
use Valkyrja\Cli\Routing\Generator\DataFileGenerator;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
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
            CollectorContract::class         => [self::class, 'publishAttributeCollector'],
            RouterContract::class            => [self::class, 'publishRouter'],
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
            RouterContract::class,
            CollectionContract::class,
            DataFileGeneratorContract::class,
        ];
    }

    /**
     * Publish the attribute collector service.
     */
    public static function publishAttributeCollector(ContainerContract $container): void
    {
        $container->setSingleton(
            CollectorContract::class,
            new AttributeCollector(
                attributes: $container->getSingleton(AttributeCollectorContract::class),
                reflection: $container->getSingleton(ReflectorContract::class),
            )
        );
    }

    /**
     * Publish the router service.
     */
    public static function publishRouter(ContainerContract $container): void
    {
        $throwableCaughtHandler   = $container->getSingleton(ThrowableCaughtHandlerContract::class);
        $commandMatchedHandler    = $container->getSingleton(RouteMatchedHandlerContract::class);
        $commandNotMatchedHandler = $container->getSingleton(RouteNotMatchedHandlerContract::class);
        $commandDispatchedHandler = $container->getSingleton(RouteDispatchedHandlerContract::class);
        $exitedHandler            = $container->getSingleton(ExitedHandlerContract::class);

        $container->setSingleton(
            RouterContract::class,
            new Router(
                container: $container,
                dispatcher: $container->getSingleton(DispatcherContract::class),
                collection: $container->getSingleton(CollectionContract::class),
                outputFactory: $container->getSingleton(OutputFactoryContract::class),
                throwableCaughtHandler: $throwableCaughtHandler,
                routeMatchedHandler: $commandMatchedHandler,
                routeNotMatchedHandler: $commandNotMatchedHandler,
                routeDispatchedHandler: $commandDispatchedHandler,
                exitedHandler: $exitedHandler
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

        $data = null;
        $env  = $container->getSingleton(Env::class);

        /** @var bool $useData */
        $useData = $env::CLI_ROUTING_COLLECTION_USE_DATA
            ?? false;
        /** @var non-empty-string $dataFilePath */
        $dataFilePath = $env::CLI_ROUTING_COLLECTION_DATA_FILE_PATH
            ?? '/cli-routes.php';
        $absoluteDataFilePath = Directory::dataPath($dataFilePath);

        if ($useData && is_file(filename: $absoluteDataFilePath)) {
            /**
             * @psalm-suppress UnresolvableInclude
             *
             * @var mixed $data The data
             */
            $data = require $absoluteDataFilePath;
        }

        if ($data instanceof Data) {
            $collection->setFromData($data);

            return;
        }

        if ($container->has(Data::class)) {
            $data = $container->getSingleton(Data::class);

            $collection->setFromData($data);

            return;
        }

        $application = $container->getSingleton(ApplicationContract::class);

        /** @var CollectorContract $collector */
        $collector   = $container->getSingleton(CollectorContract::class);
        $controllers = $application->getCliControllers();

        // Get all the attributes routes from the list of controllers
        $collection->add(
            ...$collector->getRoutes(...$controllers)
        );

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

        /** @var non-empty-string $dataFilePath */
        $dataFilePath = $env::CLI_ROUTING_COLLECTION_DATA_FILE_PATH
            ?? '/cli-routes.php';
        $absoluteDataFilePath = Directory::dataPath($dataFilePath);

        $collection = $container->getSingleton(CollectionContract::class);

        $container->setSingleton(
            DataFileGeneratorContract::class,
            new DataFileGenerator(
                filePath: $absoluteDataFilePath,
                data: $collection->getData(),
            )
        );
    }
}
