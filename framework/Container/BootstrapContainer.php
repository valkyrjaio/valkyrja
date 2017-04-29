<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Container;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;

use Psr\Log\LoggerInterface;

use Valkyrja\Annotations\Annotations;
use Valkyrja\Annotations\AnnotationsParser;
use Valkyrja\Container\Annotations\ContainerAnnotations;
use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Annotations\AnnotationsParser as AnnotationsParserContract;
use Valkyrja\Contracts\Events\Events as EventsContract;
use Valkyrja\Contracts\Container\Container as ContainerContract;
use Valkyrja\Dispatcher\Dispatch;
use Valkyrja\Events\Annotations\ListenerAnnotations;
use Valkyrja\Http\Client;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseBuilder;
use Valkyrja\Logger\Enums\LogLevel;
use Valkyrja\Logger\Logger;
use Valkyrja\Routing\Annotations\RouteAnnotations;
use Valkyrja\Routing\Router;
use Valkyrja\View\View;

/**
 * Class BootstrapContainer
 *
 * @package Valkyrja\Container
 *
 * @author  Melech Mizrachi
 */
class BootstrapContainer
{
    /**
     * The application.
     *
     * @var \Valkyrja\Contracts\Application
     */
    protected $app;

    /**
     * The events.
     *
     * @var \Valkyrja\Contracts\Events\Events
     */
    protected $events;

    /**
     * @var \Valkyrja\Contracts\Container\Container
     */
    protected $container;

    /**
     * BootstrapContainer constructor.
     *
     * @param \Valkyrja\Contracts\Application         $application The application
     * @param \Valkyrja\Contracts\Events\Events       $events      The events
     * @param \Valkyrja\Contracts\Container\Container $container
     */
    public function __construct(Application $application, EventsContract $events, ContainerContract $container)
    {
        $this->app = $application;
        $this->events = $events;
        $this->container = $container;

        $this->bootstrap();
    }

    /**
     * Bootstrap the container.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        $this->bootstrapAnnotationsParser();
        $this->bootstrapAnnotations();
        $this->bootstrapContainerAnnotations();
        $this->bootstrapListenerAnnotations();
        $this->bootstrapRequest();
        $this->bootstrapResponse();
        $this->bootstrapJsonResponse();
        $this->bootstrapRedirectResponse();
        $this->bootstrapResponseBuilder();
        $this->bootstrapRouter();
        $this->bootstrapRouteAnnotations();
        $this->bootstrapView();
        $this->bootstrapClient();
        $this->bootstrapLoggerInterface();
        $this->bootstrapLogger();
    }

    /**
     * Bootstrap the annotations parser.
     *
     * @return void
     */
    protected function bootstrapAnnotationsParser(): void
    {
        $this->container->bind(
            (new Service())
                ->setId(CoreComponent::ANNOTATIONS_PARSER)
                ->setClass(AnnotationsParser::class)
                ->setSingleton(true)
        );
    }

    /**
     * Bootstrap the annotations.
     *
     * @return void
     */
    protected function bootstrapAnnotations(): void
    {
        $this->container->bind(
            (new Service())
                ->setId(CoreComponent::ANNOTATIONS)
                ->setClass(Annotations::class)
                ->setDependencies([AnnotationsParserContract::class])
                ->setSingleton(true)
        );
    }

    /**
     * Bootstrap the container annotations.
     *
     * @return void
     */
    protected function bootstrapContainerAnnotations(): void
    {
        $this->container->bind(
            (new Service())
                ->setId(CoreComponent::CONTAINER_ANNOTATIONS)
                ->setClass(ContainerAnnotations::class)
                ->setDependencies([AnnotationsParserContract::class])
                ->setSingleton(true)
        );
    }

    /**
     * Bootstrap the listener annotations.
     *
     * @return void
     */
    protected function bootstrapListenerAnnotations(): void
    {
        $this->container->bind(
            (new Service())
                ->setId(CoreComponent::LISTENER_ANNOTATIONS)
                ->setClass(ListenerAnnotations::class)
                ->setDependencies([AnnotationsParserContract::class])
                ->setSingleton(true)
        );
    }

    /**
     * Bootstrap the request.
     *
     * @return void
     */
    protected function bootstrapRequest(): void
    {
        $this->container->bind(
            (new Service())
                ->setId(CoreComponent::REQUEST)
                ->setClass(Request::class)
                ->setMethod('createFromGlobals')
                ->setStatic(true)
                ->setSingleton(true)
        );
    }

    /**
     * Bootstrap the response.
     *
     * @return void
     */
    protected function bootstrapResponse(): void
    {
        $this->container->bind(
            (new Service())
                ->setId(CoreComponent::RESPONSE)
                ->setClass(Response::class)
        );
    }

    /**
     * Bootstrap the json response.
     *
     * @return void
     */
    protected function bootstrapJsonResponse(): void
    {
        $this->container->bind(
            (new Service())
                ->setId(CoreComponent::JSON_RESPONSE)
                ->setClass(JsonResponse::class)
        );
    }

    /**
     * Bootstrap the redirect response.
     *
     * @return void
     */
    protected function bootstrapRedirectResponse(): void
    {
        $this->container->bind(
            (new Service())
                ->setId(CoreComponent::REDIRECT_RESPONSE)
                ->setClass(RedirectResponse::class)
        );
    }

    /**
     * Bootstrap the response builder.
     *
     * @return void
     */
    protected function bootstrapResponseBuilder(): void
    {
        $this->container->bind(
            (new Service())
                ->setId(CoreComponent::RESPONSE_BUILDER)
                ->setClass(ResponseBuilder::class)
                ->setDependencies([Application::class])
                ->setSingleton(true)
        );
    }

    /**
     * Bootstrap the router.
     *
     * @return void
     */
    protected function bootstrapRouter(): void
    {
        $this->container->bind(
            (new Service())
                ->setId(CoreComponent::ROUTER)
                ->setClass(Router::class)
                ->setDependencies([Application::class])
                ->setSingleton(true)
        );
    }

    /**
     * Bootstrap the route annotations.
     *
     * @return void
     */
    protected function bootstrapRouteAnnotations(): void
    {
        $this->container->bind(
            (new Service())
                ->setId(CoreComponent::ROUTE_ANNOTATIONS)
                ->setClass(RouteAnnotations::class)
                ->setDependencies([AnnotationsParserContract::class])
                ->setSingleton(true)
        );
    }

    /**
     * Bootstrap the view.
     *
     * @return void
     */
    protected function bootstrapView(): void
    {
        $this->container->bind(
            (new Service())
                ->setId(CoreComponent::VIEW)
                ->setClass(View::class)
                ->setDependencies([Application::class])
        );
    }

    /**
     * Bootstrap the client.
     *
     * @return void
     */
    protected function bootstrapClient(): void
    {
        $this->container->bind(
            (new Service())
                ->setId(CoreComponent::CLIENT)
                ->setClass(Client::class)
                ->setSingleton(true)
        );
    }

    /**
     * Bootstrap the logger interface.
     *
     * @return void
     */
    protected function bootstrapLoggerInterface(): void
    {
        $this->container->bind(
            (new Service())
                ->setId(StreamHandler::class)
                ->setClass(StreamHandler::class)
                ->setArguments([
                    $this->app->config()->logger->filePath,
                    LogLevel::DEBUG,
                ])
                ->setSingleton(true)
        );

        $this->container->bind(
            (new Service())
                ->setId(CoreComponent::LOGGER_INTERFACE)
                ->setClass(MonologLogger::class)
                ->setDependencies([Application::class])
                ->setArguments([
                    $this->app->config()->logger->name,
                    [
                        (new Dispatch())
                            ->setClass(StreamHandler::class),
                    ],
                ])
                ->setSingleton(true)
        );
    }

    /**
     * Bootstrap the logger.
     *
     * @return void
     */
    protected function bootstrapLogger(): void
    {
        $this->container->bind(
            (new Service())
                ->setId(CoreComponent::LOGGER)
                ->setClass(Logger::class)
                ->setDependencies([LoggerInterface::class])
                ->setSingleton(true)
        );
    }
}
