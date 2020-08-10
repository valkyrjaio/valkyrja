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

namespace Valkyrja\Crypt\Providers;

use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Crypt\Adapters\SodiumAdapter;
use Valkyrja\Crypt\Crypt;
use Valkyrja\Crypt\Drivers\Driver;

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
            Crypt::class         => 'publishCrypt',
            Driver::class        => 'publishDefaultDriver',
            SodiumAdapter::class => 'publishSodiumAdapter',
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
            Crypt::class,
            Driver::class,
            SodiumAdapter::class,
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
     * Publish the crypt service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishCrypt(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Crypt::class,
            new \Valkyrja\Crypt\Managers\Crypt(
                $container,
                $config['crypt']
            )
        );
    }

    /**
     * Publish the default driver service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishDefaultDriver(Container $container): void
    {
        $container->setClosure(
            Driver::class,
            static function (string $crypt, string $adapter) use ($container): Driver {
                return new Driver(
                    $container->get(
                        $adapter,
                        [
                            $crypt,
                        ]
                    )
                );
            }
        );
    }

    /**
     * Publish the sodium adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishSodiumAdapter(Container $container): void
    {
        $config = $container->getSingleton('config');
        $crypts = $config['crypt']['crypts'];

        $container->setClosure(
            SodiumAdapter::class,
            static function (string $crypt) use ($crypts): SodiumAdapter {
                return new SodiumAdapter(
                    $crypts[$crypt]
                );
            }
        );
    }
}
