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
use Valkyrja\Events\Annotations\ListenerAnnotations;
use Valkyrja\Routing\Annotations\RouteAnnotations;
use Valkyrja\Support\ServiceProvider;

/**
 * Class AnnotationsServiceProvider.
 *
 * @author Melech Mizrachi
 */
class AnnotationsServiceProvider extends ServiceProvider
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
     * Publish the service provider.
     *
     * @return void
     */
    public function publish(): void
    {
        $this->bindAnnotationsParser();
        $this->bindAnnotations();
        $this->bindContainerAnnotations();
        $this->bindListenerAnnotations();
        $this->bindCommandAnnotations();
        $this->bindRouteAnnotations();
    }

    /**
     * Bind the annotations parser.
     *
     * @return void
     */
    protected function bindAnnotationsParser(): void
    {
        $this->app->container()->singleton(
            CoreComponent::ANNOTATIONS_PARSER,
            new AnnotationsParser(
                $this->app->container()->get(CoreComponent::APP)
            )
        );
    }

    /**
     * Bind the annotations.
     *
     * @return void
     */
    protected function bindAnnotations(): void
    {
        $this->app->container()->singleton(
            CoreComponent::ANNOTATIONS,
            new Annotations(
                $this->app->container()->get(CoreComponent::ANNOTATIONS_PARSER)
            )
        );
    }

    /**
     * Bind the container annotations.
     *
     * @return void
     */
    protected function bindContainerAnnotations(): void
    {
        $this->app->container()->singleton(
            CoreComponent::CONTAINER_ANNOTATIONS,
            new ContainerAnnotations(
                $this->app->container()->get(CoreComponent::ANNOTATIONS_PARSER)
            )
        );
    }

    /**
     * Bind the listener annotations.
     *
     * @return void
     */
    protected function bindListenerAnnotations(): void
    {
        $this->app->container()->singleton(
            CoreComponent::LISTENER_ANNOTATIONS,
            new ListenerAnnotations(
                $this->app->container()->get(CoreComponent::ANNOTATIONS_PARSER)
            )
        );
    }

    /**
     * Bind the command annotations.
     *
     * @return void
     */
    protected function bindCommandAnnotations(): void
    {
        $this->app->container()->singleton(
            CoreComponent::COMMAND_ANNOTATIONS,
            new CommandAnnotations(
                $this->app->container()->get(CoreComponent::ANNOTATIONS_PARSER)
            )
        );
    }

    /**
     * Bind the route annotations.
     *
     * @return void
     */
    protected function bindRouteAnnotations(): void
    {
        $this->app->container()->singleton(
            CoreComponent::ROUTE_ANNOTATIONS,
            new RouteAnnotations(
                $this->app->container()->get(CoreComponent::ANNOTATIONS_PARSER)
            )
        );
    }
}
