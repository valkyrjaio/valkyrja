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
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Annotations\Annotations as AnnotationsContract;
use Valkyrja\Contracts\Annotations\AnnotationsParser as AnnotationsParserContract;
use Valkyrja\Contracts\Http\Client as ClientContract;
use Valkyrja\Contracts\Container\Container as ContainerContract;
use Valkyrja\Contracts\Http\JsonResponse as JsonResponseContract;
use Valkyrja\Contracts\Http\RedirectResponse as RedirectResponseContract;
use Valkyrja\Contracts\Http\Request as RequestContract;
use Valkyrja\Contracts\Http\Response as ResponseContract;
use Valkyrja\Contracts\Http\ResponseBuilder as ResponseBuilderContract;
use Valkyrja\Contracts\Logger\Logger as LoggerContract;
use Valkyrja\Contracts\Routing\Annotations\RouteAnnotations as RouteAnnotationsContract;
use Valkyrja\Contracts\Routing\Router as RouterContract;
use Valkyrja\Contracts\View\View as ViewContract;
use Valkyrja\Dispatcher\Dispatch;
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
     * @var \Valkyrja\Contracts\Container\Container
     */
    protected $container;
    
    public function __construct(ContainerContract $container)
    {
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
        $this->bootstrapRequest();
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
                ->setId(AnnotationsParserContract::class)
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
                ->setId(AnnotationsContract::class)
                ->setClass(Annotations::class)
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
                ->setId(RequestContract::class)
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
                ->setId(ResponseContract::class)
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
                ->setId(JsonResponseContract::class)
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
                ->setId(RedirectResponseContract::class)
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
                ->setId(ResponseBuilderContract::class)
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
                ->setId(RouterContract::class)
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
                ->setId(RouteAnnotationsContract::class)
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
                ->setId(ViewContract::class)
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
                ->setId(ClientContract::class)
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
        $app = $this->container->get(Application::class);

        $this->container->bind(
            (new Service())
                ->setId(StreamHandler::class)
                ->setClass(StreamHandler::class)
                ->setArguments([
                    $app->config()->logger->filePath,
                    LogLevel::DEBUG,
                ])
                ->setSingleton(true)
        );

        $this->container->bind(
            (new Service())
                ->setId(LoggerInterface::class)
                ->setClass(MonologLogger::class)
                ->setDependencies([Application::class])
                ->setArguments([
                    $app->config()->logger->name,
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
                ->setId(LoggerContract::class)
                ->setClass(Logger::class)
                ->setDependencies([LoggerInterface::class])
                ->setSingleton(true)
        );
    }
}
