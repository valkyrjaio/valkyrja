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

use Valkyrja\Annotation\Contract\Annotations as AnnotationAnnotator;
use Valkyrja\Annotation\Filter\Contract\Filter;
use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Dispatcher\Contract\Dispatcher;
use Valkyrja\Dispatcher\Validator\Contract\Validator;
use Valkyrja\Event\Contract\Dispatcher as Events;
use Valkyrja\Http\Request;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Reflection\Contract\Reflection;
use Valkyrja\Routing\Annotator;
use Valkyrja\Routing\Attributes;
use Valkyrja\Routing\Collection;
use Valkyrja\Routing\Collections\CacheableCollection;
use Valkyrja\Routing\Collector;
use Valkyrja\Routing\Matcher;
use Valkyrja\Routing\Processor;
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
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            Annotator::class  => [self::class, 'publishAnnotator'],
            Router::class     => [self::class, 'publishRouter'],
            Collector::class  => [self::class, 'publishCollector'],
            Collection::class => [self::class, 'publishCollection'],
            Matcher::class    => [self::class, 'publishMatcher'],
            Url::class        => [self::class, 'publishUrl'],
            Attributes::class => [self::class, 'publishRouteAttributes'],
            Processor::class  => [self::class, 'publishProcessor'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Annotator::class,
            Router::class,
            Collector::class,
            Collection::class,
            Matcher::class,
            Url::class,
            Attributes::class,
            Processor::class,
        ];
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
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Router::class,
            new \Valkyrja\Routing\Dispatchers\Router(
                $container->getSingleton(Collection::class),
                $container->getSingleton(Container::class),
                $container->getSingleton(Dispatcher::class),
                $container->getSingleton(Events::class),
                $container->getSingleton(Matcher::class),
                $container->getSingleton(ResponseFactory::class),
                $config['routing'],
                $config['app']['debug']
            )
        );
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
            new \Valkyrja\Routing\Annotators\Annotator(
                $container->getSingleton(AnnotationAnnotator::class),
                $container->getSingleton(Filter::class),
                $container->getSingleton(Reflection::class),
                $container->getSingleton(Processor::class)
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
                $container->getSingleton(Collection::class),
                $container->getSingleton(Processor::class),
                $container->getSingleton(Reflection::class)
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
            $collection = new CacheableCollection(
                $container,
                $config['routing']
            )
        );

        $collection->setup();
    }

    /**
     * Publish the matcher service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishMatcher(Container $container): void
    {
        $container->setSingleton(
            Matcher::class,
            new \Valkyrja\Routing\Matchers\Matcher(
                $container->getSingleton(Collection::class)
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
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Url::class,
            new \Valkyrja\Routing\Urls\Url(
                $container->getSingleton(Request::class),
                $container->getSingleton(Router::class),
                $config['routing']
            )
        );
    }

    /**
     * Publish the route attributes service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRouteAttributes(Container $container): void
    {
        $container->setSingleton(
            Attributes::class,
            new Attributes\Attributes(
                $container->getSingleton(\Valkyrja\Attribute\Contract\Attributes::class),
                $container->getSingleton(Reflection::class),
                $container->getSingleton(Processor::class)
            )
        );
    }

    /**
     * Publish the processor service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishProcessor(Container $container): void
    {
        $container->setSingleton(
            Processor::class,
            new \Valkyrja\Routing\Processors\Processor(
                $container->getSingleton(Validator::class)
            )
        );
    }
}
