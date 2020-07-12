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

namespace Valkyrja\Routing\Providers;

use Valkyrja\Annotation\Annotator as AnnotationAnnotator;
use Valkyrja\Annotation\Filter;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Event\Events;
use Valkyrja\Http\Request;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Path\PathGenerator;
use Valkyrja\Path\PathParser;
use Valkyrja\Reflection\Reflector;
use Valkyrja\Routing\Annotation\Annotator;
use Valkyrja\Routing\Collections\CacheableCollection;
use Valkyrja\Routing\Collector;
use Valkyrja\Routing\Matchers\Matcher;
use Valkyrja\Routing\Router;
use Valkyrja\Routing\Url;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProvider extends Provider
{
    /**
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function publishers(): array
    {
        return [
            Annotator::class => 'publishAnnotator',
            Router::class    => 'publishRouter',
            Collector::class => 'publishCollector',
            Url::class       => 'publishUrl',
        ];
    }

    /**
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function provides(): array
    {
        return [
            Annotator::class,
            Router::class,
            Collector::class,
            Url::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
    }

    /**
     * Publish the router service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRouter(Container $container): void
    {
        $config        = $container->getSingleton('config');
        $routingConfig = (array) $config['routing'];

        $container->setSingleton(
            Router::class,
            new \Valkyrja\Routing\Dispatchers\Router(
                $container->getSingleton(Container::class),
                $dispatcher = $container->getSingleton(Dispatcher::class),
                $container->getSingleton(Events::class),
                $container->getSingleton(Request::class),
                $container->getSingleton(ResponseFactory::class),
                $collection = new CacheableCollection(
                    $container,
                    $dispatcher,
                    new Matcher(),
                    $routingConfig
                ),
                $routingConfig,
                $config['app']['debug']
            )
        );

        $collection->setup();
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
            new \Valkyrja\Routing\Annotation\Annotators\Annotator(
                $container->getSingleton(AnnotationAnnotator::class),
                $container->getSingleton(Filter::class),
                $container->getSingleton(Reflector::class)
            )
        );
    }

    /**
     * Publish the collector service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishCollector(Container $container): void
    {
        $container->setSingleton(
            Collector::class,
            new \Valkyrja\Routing\Collectors\Collector(
                $container->getSingleton(PathParser::class),
                $container->getSingleton(Router::class)
            )
        );
    }

    /**
     * Publish the url service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishUrl(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Url::class,
            new \Valkyrja\Routing\Urls\Url(
                $container->getSingleton(PathGenerator::class),
                $container->getSingleton(Request::class),
                $container->getSingleton(Router::class),
                (array) $config['routing']
            )
        );
    }
}
