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
use Valkyrja\Jwt\Adapter\Firebase\EdDsaAdapter;
use Valkyrja\Jwt\Adapter\Firebase\HsAdapter;
use Valkyrja\Jwt\Adapter\Firebase\RsAdapter;
use Valkyrja\Jwt\Contract\Jwt;
use Valkyrja\Jwt\Driver\Driver;
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
            Jwt::class          => [self::class, 'publishJwt'],
            Factory::class      => [self::class, 'publishFactory'],
            Driver::class       => [self::class, 'publishDriver'],
            EdDsaAdapter::class => [self::class, 'publishEdDsaAdapter'],
            HsAdapter::class    => [self::class, 'publishHsAdapter'],
            RsAdapter::class    => [self::class, 'publishRsAdapter'],
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
            EdDsaAdapter::class,
            HsAdapter::class,
            RsAdapter::class,
        ];
    }

    /**
     * Publish the JWT service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishJwt(Container $container): void
    {
        /** @var array{jwt: \Valkyrja\Jwt\Config|array<string, mixed>, ...} $config */
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Jwt::class,
            new \Valkyrja\Jwt\Jwt(
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
     * Publish the EdDsa adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishEdDsaAdapter(Container $container): void
    {
        $container->setCallable(
            Adapter::class,
            [static::class, 'createEdDsaAdapter']
        );
    }

    /**
     * Create the EdDsa adapter.
     *
     * @param Container            $container
     * @param array<string, mixed> $config
     *
     * @return EdDsaAdapter
     */
    public static function createEdDsaAdapter(Container $container, array $config): Adapter
    {
        return new EdDsaAdapter(
            $config,
        );
    }

    /**
     * Publish the HS adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishHsAdapter(Container $container): void
    {
        $container->setCallable(
            Adapter::class,
            [static::class, 'createHsAdapter']
        );
    }

    /**
     * Create the HS adapter.
     *
     * @param Container            $container
     * @param array<string, mixed> $config
     *
     * @return HsAdapter
     */
    public static function createHsAdapter(Container $container, array $config): Adapter
    {
        return new HsAdapter(
            $config,
        );
    }

    /**
     * Publish the RS adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRsAdapter(Container $container): void
    {
        $container->setCallable(
            RsAdapter::class,
            [static::class, 'createRsAdapter']
        );
    }

    /**
     * Create the RS adapter.
     *
     * @param Container            $container
     * @param array<string, mixed> $config
     *
     * @return RsAdapter
     */
    public static function createRsAdapter(Container $container, array $config): Adapter
    {
        return new RsAdapter(
            $config,
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
        $container->setCallable(
            Driver::class,
            [static::class, 'createDriver']
        );
    }

    /**
     * Create the driver.
     *
     * @param Container $container
     * @param Adapter   $adapter
     *
     * @return Driver
     */
    public static function createDriver(Container $container, Adapter $adapter): Driver
    {
        return new Driver(
            $adapter
        );
    }
}
