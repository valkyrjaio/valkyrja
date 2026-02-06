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

namespace Valkyrja\Http\Routing\Provider;

use Override;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Attribute\Collector\Contract\CollectorContract as AttributeCollectorContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Provider;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract as HttpMessageResponseFactory;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Http\Routing\Collection\Collection;
use Valkyrja\Http\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Http\Routing\Collector\AttributeCollector;
use Valkyrja\Http\Routing\Collector\Contract\CollectorContract;
use Valkyrja\Http\Routing\Data\Data;
use Valkyrja\Http\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Http\Routing\Dispatcher\Router;
use Valkyrja\Http\Routing\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Routing\Factory\ResponseFactory;
use Valkyrja\Http\Routing\Generator\Contract\DataFileGeneratorContract;
use Valkyrja\Http\Routing\Generator\DataFileGenerator;
use Valkyrja\Http\Routing\Matcher\Contract\MatcherContract;
use Valkyrja\Http\Routing\Matcher\Matcher;
use Valkyrja\Http\Routing\Processor\Contract\ProcessorContract;
use Valkyrja\Http\Routing\Processor\Processor;
use Valkyrja\Http\Routing\Url\Contract\UrlContract;
use Valkyrja\Http\Routing\Url\Url;
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
            RouterContract::class            => [self::class, 'publishRouter'],
            CollectionContract::class        => [self::class, 'publishCollection'],
            DataFileGeneratorContract::class => [self::class, 'publishDataFileGenerator'],
            MatcherContract::class           => [self::class, 'publishMatcher'],
            UrlContract::class               => [self::class, 'publishUrl'],
            CollectorContract::class         => [self::class, 'publishAttributesCollector'],
            ProcessorContract::class         => [self::class, 'publishProcessor'],
            ResponseFactoryContract::class   => [self::class, 'publishResponseFactory'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            RouterContract::class,
            CollectionContract::class,
            DataFileGeneratorContract::class,
            MatcherContract::class,
            UrlContract::class,
            CollectorContract::class,
            ProcessorContract::class,
            ResponseFactoryContract::class,
        ];
    }

    /**
     * Publish the router service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishRouter(ContainerContract $container): void
    {
        $exception       = $container->getSingleton(ThrowableCaughtHandlerContract::class);
        $routeMatched    = $container->getSingleton(RouteMatchedHandlerContract::class);
        $routeNotMatched = $container->getSingleton(RouteNotMatchedHandlerContract::class);
        $routeDispatched = $container->getSingleton(RouteDispatchedHandlerContract::class);
        $sendingResponse = $container->getSingleton(SendingResponseHandlerContract::class);
        $terminated      = $container->getSingleton(TerminatedHandlerContract::class);

        $container->setSingleton(
            RouterContract::class,
            new Router(
                container: $container,
                dispatcher: $container->getSingleton(DispatcherContract::class),
                matcher: $container->getSingleton(MatcherContract::class),
                responseFactory: $container->getSingleton(HttpMessageResponseFactory::class),
                throwableCaughtHandler: $exception,
                routeMatchedHandler: $routeMatched,
                routeNotMatchedHandler: $routeNotMatched,
                routeDispatchedHandler: $routeDispatched,
                sendingResponseHandler: $sendingResponse,
                terminatedHandler: $terminated
            )
        );
    }

    /**
     * Publish the collection service.
     *
     * @param ContainerContract $container The container
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
        $useData = $env::HTTP_ROUTING_COLLECTION_USE_DATA
            ?? false;
        /** @var non-empty-string $dataFilePath */
        $dataFilePath = $env::HTTP_ROUTING_COLLECTION_DATA_FILE_PATH
            ?? '/routes.php';
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
        $controllers = $application->getHttpControllers();

        // Get all the attributes routes from the list of controllers
        // Iterate through the routes
        foreach ($collector->getRoutes(...$controllers) as $route) {
            // Set the route
            $collection->add($route);
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

        /** @var non-empty-string $dataFilePath */
        $dataFilePath = $env::HTTP_ROUTING_COLLECTION_DATA_FILE_PATH
            ?? '/routes.php';
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

    /**
     * Publish the matcher service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishMatcher(ContainerContract $container): void
    {
        $container->setSingleton(
            MatcherContract::class,
            new Matcher(
                collection: $container->getSingleton(CollectionContract::class)
            )
        );
    }

    /**
     * Publish the url service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishUrl(ContainerContract $container): void
    {
        $container->setSingleton(
            UrlContract::class,
            new Url(
                collection: $container->getSingleton(CollectionContract::class),
            )
        );
    }

    /**
     * Publish the route attributes service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishAttributesCollector(ContainerContract $container): void
    {
        $container->setSingleton(
            CollectorContract::class,
            new AttributeCollector(
                attributes: $container->getSingleton(AttributeCollectorContract::class),
                reflection: $container->getSingleton(ReflectorContract::class),
                processor: $container->getSingleton(ProcessorContract::class)
            )
        );
    }

    /**
     * Publish the processor service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishProcessor(ContainerContract $container): void
    {
        $container->setSingleton(
            ProcessorContract::class,
            new Processor()
        );
    }

    /**
     * Publish the processor service.
     *
     * @param ContainerContract $container The container
     */
    public static function publishResponseFactory(ContainerContract $container): void
    {
        $container->setSingleton(
            ResponseFactoryContract::class,
            new ResponseFactory(
                responseFactory: $container->getSingleton(HttpMessageResponseFactory::class),
                url: $container->getSingleton(UrlContract::class),
            )
        );
    }
}
