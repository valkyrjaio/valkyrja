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
use Valkyrja\Contracts\Application;
use Valkyrja\Http\Kernel;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Support\Provider;

/**
 * Class HttpServiceProvider.
 *
 * @author Melech Mizrachi
 */
class HttpServiceProvider extends Provider
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
     * Publish the provider.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        static::bindKernel($app);
        static::bindRequest($app);
        static::bindResponse($app);
    }

    /**
     * Bind the kernel.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    protected static function bindKernel(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::KERNEL,
            new Kernel($app, $app->router())
        );
    }

    /**
     * Bind the request.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    protected static function bindRequest(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::REQUEST,
            Request::createFromGlobals()
        );
    }

    /**
     * Bind the response.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected static function bindResponse(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::RESPONSE,
            new Response()
        );
    }
}
