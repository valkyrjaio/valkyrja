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
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Support\Provider;

/**
 * Class RedirectResponseServiceProvider.
 *
 * @author Melech Mizrachi
 */
class RedirectResponseServiceProvider extends Provider
{
    /**
     * What services are provided.
     *
     * @var array
     */
    public static $provides = [
        CoreComponent::REDIRECT_RESPONSE,
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
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        static::bindRedirectResponse($app);
    }

    /**
     * Bootstrap the redirect response.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected static function bindRedirectResponse(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::REDIRECT_RESPONSE,
            new RedirectResponse()
        );
    }
}
