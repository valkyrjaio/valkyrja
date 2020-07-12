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
use Valkyrja\Crypt\Crypt;
use Valkyrja\Crypt\Decrypter;
use Valkyrja\Crypt\Decrypters\SodiumDecrypter;
use Valkyrja\Crypt\Encrypter;
use Valkyrja\Crypt\Encrypters\SodiumEncrypter;

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
            Decrypter::class => 'publishCrypt',
            Encrypter::class => 'publishEncrypter',
            Crypt::class     => 'publishDecrypter',
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
            Decrypter::class,
            Encrypter::class,
            Crypt::class,
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
            new \Valkyrja\Crypt\Crypts\Crypt(
                $container->get(Encrypter::class),
                $container->get(Decrypter::class),
                (array) $config['crypt']
            )
        );
    }

    /**
     * Publish the decrypter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishDecrypter(Container $container): void
    {
        $container->setSingleton(
            Decrypter::class,
            new SodiumDecrypter()
        );
    }

    /**
     * Publish the encrypter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishEncrypter(Container $container): void
    {
        $container->setSingleton(
            Encrypter::class,
            new SodiumEncrypter()
        );
    }
}
