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

use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Crypt\Crypt;
use Valkyrja\Crypt\Decrypters\SodiumAdapter;

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
     * Publish the sodium adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishSodiumAdapter(Container $container): void
    {
        $container->setSingleton(
            SodiumAdapter::class,
            new SodiumAdapter()
        );
    }
}
