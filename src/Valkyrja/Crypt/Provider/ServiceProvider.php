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

use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Crypt\Adapter\Contract\Adapter;
use Valkyrja\Crypt\Adapter\NullAdapter;
use Valkyrja\Crypt\Adapter\SodiumAdapter;
use Valkyrja\Crypt\Contract\Crypt;
use Valkyrja\Crypt\Driver\Driver;
use Valkyrja\Crypt\Factory\ContainerFactory;
use Valkyrja\Crypt\Factory\Contract\Factory;

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
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishCrypt(Container $container): void
    {
        /** @var array{crypt: \Valkyrja\Crypt\Config|array<string, mixed>, ...} $config */
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Crypt::class,
            new \Valkyrja\Crypt\Crypt(
                $container->getSingleton(Factory::class),
                $config['crypt']
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
     * Publish the default driver service.
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
     * Create a driver.
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

    /**
     * Publish the null adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishNullAdapter(Container $container): void
    {
        $container->setCallable(
            NullAdapter::class,
            [static::class, 'createNullAdapter']
        );
    }

    /**
     * Create a null adapter.
     *
     * @param Container            $container
     * @param array<string, mixed> $config
     *
     * @return NullAdapter
     */
    public static function createNullAdapter(Container $container, array $config): NullAdapter
    {
        return new NullAdapter(
            $config
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
        $container->setCallable(
            SodiumAdapter::class,
            [static::class, 'createSodiumAdapter']
        );
    }

    /**
     * Create a sodium adapter.
     *
     * @param Container            $container
     * @param array<string, mixed> $config
     *
     * @return SodiumAdapter
     */
    public static function createSodiumAdapter(Container $container, array $config): SodiumAdapter
    {
        return new SodiumAdapter(
            $config
        );
    }
}
