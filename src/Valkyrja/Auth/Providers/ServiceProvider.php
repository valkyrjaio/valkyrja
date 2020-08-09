<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Auth\Providers;

use Valkyrja\Auth\Auth;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Crypt\Crypt;
use Valkyrja\ORM\ORM;
use Valkyrja\Session\Session;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProvider extends Provider
{
    /**
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function publishers(): array
    {
        return [
            Auth::class => 'publishAuth',
        ];
    }

    /**
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function provides(): array
    {
        return [
            Auth::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
    }

    /**
     * Publish the auth service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishAuth(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Auth::class,
            new \Valkyrja\Auth\Managers\Auth(
                $container->getSingleton(Crypt::class),
                $container->getSingleton(ORM::class),
                $container->getSingleton(Session::class),
                $config['auth']
            )
        );
    }
}
