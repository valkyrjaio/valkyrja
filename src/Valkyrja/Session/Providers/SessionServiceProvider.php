<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Session\Providers;

use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Contracts\Application;
use Valkyrja\Session\Session;
use Valkyrja\Support\Provider;

/**
 * Class SessionServiceProvider.
 *
 * @author Melech Mizrachi
 */
class SessionServiceProvider extends Provider
{
    /**
     * What services are provided.
     *
     * @var array
     */
    public static $provides = [
        CoreComponent::SESSION,
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
     * @throws \Valkyrja\Session\Exceptions\InvalidSessionId
     * @throws \Valkyrja\Session\Exceptions\SessionStartFailure
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        static::bindSession($app);
    }

    /**
     * Bind the session.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @throws \Valkyrja\Session\Exceptions\InvalidSessionId
     * @throws \Valkyrja\Session\Exceptions\SessionStartFailure
     *
     * @return void
     */
    protected static function bindSession(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::SESSION,
            new Session($app)
        );
    }
}
