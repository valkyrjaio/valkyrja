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
use Valkyrja\Crypt\Adapter;
use Valkyrja\Crypt\Crypt;
use Valkyrja\Crypt\Driver;
use Valkyrja\Crypt\Loader;
use Valkyrja\Crypt\Loaders\ContainerLoader;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    public static function publishers(): array
    {
        return [
            Crypt::class   => 'publishCrypt',
            Loader::class  => 'publishLoader',
            Driver::class  => 'publishDriver',
            Adapter::class => 'publishAdapter',
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Crypt::class,
            Loader::class,
            Driver::class,
            Adapter::class,
        ];
    }

    /**
     * @inheritDoc
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
                $container->getSingleton(Loader::class),
                $config['crypt']
            )
        );
    }

    /**
     * Publish the loader service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishLoader(Container $container): void
    {
        $container->setSingleton(
            Loader::class,
            new ContainerLoader($container),
        );
    }

    /**
     * Publish the default driver service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishDriver(Container $container): void
    {
        $container->setClosure(
            Driver::class,
            static function (string $name, Adapter $adapter): Driver {
                return new $name(
                    $adapter
                );
            }
        );
    }

    /**
     * Publish the adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishAdapter(Container $container): void
    {
        $container->setClosure(
            Adapter::class,
            static function (string $name, array $config): Adapter {
                return new $name(
                    $config
                );
            }
        );
    }
}
