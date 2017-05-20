<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Providers;

use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Http\Kernel;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Support\ServiceProvider;

/**
 * Class HttpServiceProvider.
 *
 * @author Melech Mizrachi
 */
class HttpServiceProvider extends ServiceProvider
{
    /**
     * What services are provided.
     *
     * @var array
     */
    public static $provides = [
        CoreComponent::KERNEL,
        CoreComponent::REQUEST,
        CoreComponent::RESPONSE,
    ];

    /**
     * Publish the service provider.
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function publish(): void
    {
        $this->bindKernel();
        $this->bindRequest();
        $this->bindResponse();
    }

    /**
     * Bind the kernel.
     *
     * @return void
     */
    protected function bindKernel(): void
    {
        $this->app->container()->singleton(
            CoreComponent::KERNEL,
            new Kernel($this->app, $this->app->router())
        );
    }

    /**
     * Bind the request.
     *
     * @return void
     */
    protected function bindRequest(): void
    {
        $this->app->container()->singleton(
            CoreComponent::REQUEST,
            Request::createFromGlobals()
        );
    }

    /**
     * Bind the response.
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected function bindResponse(): void
    {
        $this->app->container()->singleton(
            CoreComponent::RESPONSE,
            new Response()
        );
    }
}
