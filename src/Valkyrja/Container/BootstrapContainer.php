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

use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Container\Container as ContainerContract;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\Kernel;
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseBuilder;
use Valkyrja\Path\PathGenerator;
use Valkyrja\Path\PathParser;
use Valkyrja\View\View;

/**
 * Class BootstrapContainer.
 *
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
     * @var \Valkyrja\Contracts\Container\Container
     */
    protected $container;

    /**
     * BootstrapContainer constructor.
     *
     * @param \Valkyrja\Contracts\Application         $application The application
     * @param \Valkyrja\Contracts\Container\Container $container
     */
    public function __construct(Application $application, ContainerContract $container)
    {
        $this->app       = $application;
        $this->container = $container;

        $this->bootstrap();
    }

    /**
     * Bootstrap the container.
     *
     * @return void
     */
    protected function bootstrap(): void
    {
        $this->bootstrapPathGenerator();
        $this->bootstrapPathParser();
        $this->bootstrapKernel();
        $this->bootstrapRequest();
        $this->bootstrapResponse();
        $this->bootstrapJsonResponse();
        $this->bootstrapRedirectResponse();
        $this->bootstrapResponseBuilder();
        $this->bootstrapView();
    }

    /**
     * Bootstrap the path generator.
     *
     * @return void
     */
    protected function bootstrapPathGenerator(): void
    {
        $this->container->bind(
            (new Service())
                ->setId(CoreComponent::PATH_GENERATOR)
                ->setClass(PathGenerator::class)
                ->setSingleton(true)
        );
    }

    /**
     * Bootstrap the path parser.
     *
     * @return void
     */
    protected function bootstrapPathParser(): void
    {
        $this->container->bind(
            (new Service())
                ->setId(CoreComponent::PATH_PARSER)
                ->setClass(PathParser::class)
                ->setSingleton(true)
        );
    }

    /**
     * Bootstrap the kernel.
     *
     * @return void
     */
    protected function bootstrapKernel(): void
    {
        $this->container->bind(
            (new Service())
                ->setId(CoreComponent::KERNEL)
                ->setClass(Kernel::class)
                ->setDependencies([CoreComponent::APP, CoreComponent::ROUTER])
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
                ->setDependencies([CoreComponent::APP])
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
                ->setDependencies([CoreComponent::APP])
        );
    }
}
