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
use Valkyrja\Application\Directory\Directory;
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
use Valkyrja\Cli\Routing\Provider\Contract\ProviderContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
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
            CollectorContract::class         => [self::class, 'publishAttributeCollector'],
            RouterContract::class            => [self::class, 'publishRouter'],
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
            RouterContract::class,
            CollectionContract::class,
            DataFileGeneratorContract::class,
            Data::class,
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
        $className = $env::CLI_ROUTING_DATA_PROVIDER_CLASS_NAME
            ?? 'CliRoutingDataProvider';

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

        /** @var CollectorContract $collector */
        $collector   = $container->getSingleton(CollectorContract::class);

        $providers = $application->getCliProviders();

        $controllers     = [];
        $routes          = [];

        /** @var ProviderContract $provider */
        foreach ($providers as $provider) {
            $controllers = [
                ...$controllers,
                ...$provider::getControllerClasses(),
            ];

            $routes = [
                ...$routes,
                ...$provider::getRoutes(),
            ];
        }

        // Get all the attributes routes from the list of controllers
        $collection->add(
            ...$collector->getRoutes(...$controllers)
        );
        $collection->add(...$routes);

        $dataGenerator = $container->getSingleton(DataFileGeneratorContract::class);
        $dataGenerator->generateFile();

        $container->setSingleton(Data::class, $collection->getData());
    }
}
