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

namespace Valkyrja\Jwt\Provider;

use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Jwt\Adapter\Contract\Adapter;
use Valkyrja\Jwt\Contract\Jwt;
use Valkyrja\Jwt\Driver\Contract\Driver;
use Valkyrja\Jwt\Factory\ContainerFactory;
use Valkyrja\Jwt\Factory\Contract\Factory;

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
            Jwt::class     => [self::class, 'publishJWT'],
            Factory::class => [self::class, 'publishFactory'],
            Driver::class  => [self::class, 'publishDriver'],
            Adapter::class => [self::class, 'publishAdapter'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Jwt::class,
            Factory::class,
            Driver::class,
            Adapter::class,
        ];
    }

    /**
     * Publish the JWT service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishJWT(Container $container): void
    {
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Jwt::class,
            new \Valkyrja\Jwt\Managers\Jwt(
                $container->getSingleton(Factory::class),
                $config['jwt']
            )
        );
    }

    /**
     * Publish the factory service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishFactory(Container $container): void
    {
        $container->setSingleton(
            Factory::class,
            new ContainerFactory($container),
        );
    }

    /**
     * Publish an adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishAdapter(Container $container): void
    {
        $container->setClosure(
            Adapter::class,
            /**
             * @param class-string<Adapter> $name
             */
            static function (string $name, array $config): Adapter {
                return new $name(
                    $config,
                );
            }
        );
    }

    /**
     * Publish a driver service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishDriver(Container $container): void
    {
        $container->setClosure(
            Driver::class,
            /**
             * @param class-string<Driver> $name
             */
            static function (string $name, Adapter $adapter): Driver {
                return new $name(
                    $adapter
                );
            }
        );
    }
}
