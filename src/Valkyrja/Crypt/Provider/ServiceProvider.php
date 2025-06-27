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

namespace Valkyrja\Crypt\Provider;

use Valkyrja\Application\Config\ValkyrjaConfig;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Crypt\Adapter\Contract\Adapter;
use Valkyrja\Crypt\Adapter\NullAdapter;
use Valkyrja\Crypt\Adapter\SodiumAdapter;
use Valkyrja\Crypt\Config\NullConfiguration;
use Valkyrja\Crypt\Config\SodiumConfiguration;
use Valkyrja\Crypt\Contract\Crypt;
use Valkyrja\Crypt\Driver\Driver;
use Valkyrja\Crypt\Factory\ContainerFactory;
use Valkyrja\Crypt\Factory\Contract\Factory;

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
            Crypt::class         => [self::class, 'publishCrypt'],
            Factory::class       => [self::class, 'publishFactory'],
            Driver::class        => [self::class, 'publishDriver'],
            NullAdapter::class   => [self::class, 'publishNullAdapter'],
            SodiumAdapter::class => [self::class, 'publishSodiumAdapter'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Crypt::class,
            Factory::class,
            Driver::class,
            NullAdapter::class,
            SodiumAdapter::class,
        ];
    }

    /**
     * Publish the crypt service.
     */
    public static function publishCrypt(Container $container): void
    {
        $config = $container->getSingleton(ValkyrjaConfig::class);

        $container->setSingleton(
            Crypt::class,
            new \Valkyrja\Crypt\Crypt(
                $container->getSingleton(Factory::class),
                $config->crypt
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
     * Publish the default driver service.
     */
    public static function publishDriver(Container $container): void
    {
        $container->setCallable(
            Driver::class,
            [self::class, 'createDriver']
        );
    }

    /**
     * Create a driver.
     */
    public static function createDriver(Container $container, Adapter $adapter): Driver
    {
        return new Driver(
            $adapter
        );
    }

    /**
     * Publish the null adapter service.
     */
    public static function publishNullAdapter(Container $container): void
    {
        $container->setCallable(
            NullAdapter::class,
            [self::class, 'createNullAdapter']
        );
    }

    /**
     * Create a null adapter.
     */
    public static function createNullAdapter(Container $container, NullConfiguration $config): NullAdapter
    {
        return new NullAdapter(
            $config
        );
    }

    /**
     * Publish the sodium adapter service.
     */
    public static function publishSodiumAdapter(Container $container): void
    {
        $container->setCallable(
            SodiumAdapter::class,
            [self::class, 'createSodiumAdapter']
        );
    }

    /**
     * Create a sodium adapter.
     */
    public static function createSodiumAdapter(Container $container, SodiumConfiguration $config): SodiumAdapter
    {
        return new SodiumAdapter(
            $config
        );
    }
}
