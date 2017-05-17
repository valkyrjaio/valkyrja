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
use Valkyrja\Container\Service;
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
        $this->app->container()->bind(
            (new Service())
                ->setId(CoreComponent::KERNEL)
                ->setClass(Kernel::class)
                ->setDependencies([CoreComponent::APP, CoreComponent::ROUTER])
                ->setSingleton(true)
        );
    }

    /**
     * Bind the request.
     *
     * @return void
     */
    protected function bindRequest(): void
    {
        $this->app->container()->bind(
            (new Service())
                ->setId(CoreComponent::REQUEST)
                ->setClass(Request::class)
                ->setMethod('createFromGlobals')
                ->setStatic(true)
                ->setSingleton(true)
        );
    }

    /**
     * Bind the response.
     *
     * @return void
     */
    protected function bindResponse(): void
    {
        $this->app->container()->bind(
            (new Service())
                ->setId(CoreComponent::RESPONSE)
                ->setClass(Response::class)
        );
    }
}
