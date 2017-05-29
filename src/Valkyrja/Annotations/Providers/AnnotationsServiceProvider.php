<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Annotations\Providers;

use Valkyrja\Annotations\Annotations;
use Valkyrja\Annotations\AnnotationsParser;
use Valkyrja\Console\Annotations\CommandAnnotations;
use Valkyrja\Container\Annotations\ContainerAnnotations;
use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Contracts\Application;
use Valkyrja\Events\Annotations\ListenerAnnotations;
use Valkyrja\Routing\Annotations\RouteAnnotations;
use Valkyrja\Support\Provider;

/**
 * Class AnnotationsServiceProvider.
 *
 * @author Melech Mizrachi
 */
class AnnotationsServiceProvider extends Provider
{
    /**
     * What services are provided.
     *
     * @var array
     */
    public static $provides = [
        CoreComponent::ANNOTATIONS_PARSER,
        CoreComponent::ANNOTATIONS,
        CoreComponent::CONTAINER_ANNOTATIONS,
        CoreComponent::LISTENER_ANNOTATIONS,
        CoreComponent::COMMAND_ANNOTATIONS,
        CoreComponent::ROUTE_ANNOTATIONS,
    ];

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return static::$provides;
    }

    /**
     * Publish the provider.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        static::bindAnnotationsParser($app);
        static::bindAnnotations($app);
        static::bindContainerAnnotations($app);
        static::bindListenerAnnotations($app);
        static::bindCommandAnnotations($app);
        static::bindRouteAnnotations($app);
    }

    /**
     * Bind the annotations parser.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    protected static function bindAnnotationsParser(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::ANNOTATIONS_PARSER,
            new AnnotationsParser(
                $app
            )
        );
    }

    /**
     * Bind the annotations.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    protected static function bindAnnotations(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::ANNOTATIONS,
            new Annotations(
                $app->container()->getSingleton(CoreComponent::ANNOTATIONS_PARSER)
            )
        );
    }

    /**
     * Bind the container annotations.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    protected static function bindContainerAnnotations(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::CONTAINER_ANNOTATIONS,
            new ContainerAnnotations(
                $app->container()->getSingleton(CoreComponent::ANNOTATIONS_PARSER)
            )
        );
    }

    /**
     * Bind the listener annotations.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    protected static function bindListenerAnnotations(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::LISTENER_ANNOTATIONS,
            new ListenerAnnotations(
                $app->container()->getSingleton(CoreComponent::ANNOTATIONS_PARSER)
            )
        );
    }

    /**
     * Bind the command annotations.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    protected static function bindCommandAnnotations(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::COMMAND_ANNOTATIONS,
            new CommandAnnotations(
                $app->container()->getSingleton(CoreComponent::ANNOTATIONS_PARSER)
            )
        );
    }

    /**
     * Bind the route annotations.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    protected static function bindRouteAnnotations(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::ROUTE_ANNOTATIONS,
            new RouteAnnotations(
                $app->container()->getSingleton(CoreComponent::ANNOTATIONS_PARSER)
            )
        );
    }
}
