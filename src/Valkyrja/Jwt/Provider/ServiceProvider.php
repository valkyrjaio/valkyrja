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

use Valkyrja\Application\Config\Valkyrja;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Jwt\Adapter\Contract\Adapter;
use Valkyrja\Jwt\Adapter\Firebase\EdDsaAdapter;
use Valkyrja\Jwt\Adapter\Firebase\HsAdapter;
use Valkyrja\Jwt\Adapter\Firebase\RsAdapter;
use Valkyrja\Jwt\Config\EdDsaConfiguration;
use Valkyrja\Jwt\Config\HsConfiguration;
use Valkyrja\Jwt\Config\RsConfiguration;
use Valkyrja\Jwt\Contract\Jwt;
use Valkyrja\Jwt\Driver\Driver;
use Valkyrja\Jwt\Factory\ContainerFactory;
use Valkyrja\Jwt\Factory\Contract\Factory;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
final class ServiceProvider extends Provider
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
     */
    public static function publishJwt(Container $container): void
    {
        $config = $container->getSingleton(Valkyrja::class);

        $container->setSingleton(
            Jwt::class,
            new \Valkyrja\Jwt\Jwt(
                $container->getSingleton(Factory::class),
                $config->jwt
            )
        );
    }

    /**
     * Publish the factory service.
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
     */
    public static function publishEdDsaAdapter(Container $container): void
    {
        $container->setCallable(
            Adapter::class,
            [self::class, 'createEdDsaAdapter']
        );
    }

    /**
     * Create the EdDsa adapter.
     */
    public static function createEdDsaAdapter(Container $container, EdDsaConfiguration $config): Adapter
    {
        return new EdDsaAdapter(
            $config,
        );
    }

    /**
     * Publish the HS adapter service.
     */
    public static function publishHsAdapter(Container $container): void
    {
        $container->setCallable(
            Adapter::class,
            [self::class, 'createHsAdapter']
        );
    }

    /**
     * Create the HS adapter.
     */
    public static function createHsAdapter(Container $container, HsConfiguration $config): Adapter
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
            [self::class, 'createRsAdapter']
        );
    }

    /**
     * Create the RS adapter.
     */
    public static function createRsAdapter(Container $container, RsConfiguration $config): Adapter
    {
        return new RsAdapter(
            $config,
        );
    }

    /**
     * Publish a driver service.
     */
    public static function publishDriver(Container $container): void
    {
        $container->setCallable(
            Driver::class,
            [self::class, 'createDriver']
        );
    }

    /**
     * Create the driver.
     */
    public static function createDriver(Container $container, Adapter $adapter): Driver
    {
        return new Driver(
            $adapter
        );
    }
}
