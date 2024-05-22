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

namespace Valkyrja\Client\Provider;

use GuzzleHttp\Client as Guzzle;
use Valkyrja\Client\Adapter\Contract\Adapter;
use Valkyrja\Client\Adapter\Contract\GuzzleAdapter;
use Valkyrja\Client\Adapter\Contract\LogAdapter;
use Valkyrja\Client\Contract\Client;
use Valkyrja\Client\Driver\Contract\Driver;
use Valkyrja\Client\Factory\ContainerFactory;
use Valkyrja\Client\Factory\Contract\Factory;
use Valkyrja\Config\Config\Config;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Log\Contract\Logger;

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
            Client::class        => [self::class, 'publishClient'],
            Factory::class       => [self::class, 'publishFactory'],
            Driver::class        => [self::class, 'publishDriver'],
            GuzzleAdapter::class => [self::class, 'publishGuzzleAdapter'],
            LogAdapter::class    => [self::class, 'publishLogAdapter'],
            Adapter::class       => [self::class, 'publishAdapter'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Client::class,
            Factory::class,
            Driver::class,
            GuzzleAdapter::class,
            LogAdapter::class,
            Adapter::class,
        ];
    }

    /**
     * Publish the client service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishClient(Container $container): void
    {
        $config = $container->getSingleton(Config::class);

        $container->setSingleton(
            Client::class,
            new \Valkyrja\Client\Client(
                $container->getSingleton(Factory::class),
                $config['client']
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
     * Publish a driver.
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
            static fn (string $name, Adapter $adapter): Driver => new $name($adapter)
        );
    }

    /**
     * Publish a guzzle adapter.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishGuzzleAdapter(Container $container): void
    {
        $responseFactory = $container->getSingleton(ResponseFactory::class);

        $container->setClosure(
            GuzzleAdapter::class,
            /**
             * @param class-string<GuzzleAdapter> $name
             */
            static function (string $name, array $config) use ($responseFactory): GuzzleAdapter {
                return new $name(
                    new Guzzle($config['options'] ?? []),
                    $responseFactory,
                    $config
                );
            }
        );
    }

    /**
     * Publish a log adapter.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishLogAdapter(Container $container): void
    {
        $logger          = $container->getSingleton(Logger::class);
        $responseFactory = $container->getSingleton(ResponseFactory::class);

        $container->setClosure(
            LogAdapter::class,
            /**
             * @param class-string<LogAdapter> $name
             */
            static function (string $name, array $config) use ($logger, $responseFactory): LogAdapter {
                return new $name(
                    $logger->use($config['logger'] ?? null),
                    $responseFactory,
                    $config
                );
            }
        );
    }

    /**
     * Publish an adapter.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishAdapter(Container $container): void
    {
        $responseFactory = $container->getSingleton(ResponseFactory::class);

        $container->setClosure(
            Adapter::class,
            /**
             * @param class-string<Adapter> $name
             */
            static function (string $name, array $config) use ($responseFactory): Adapter {
                return new $name(
                    $responseFactory,
                    $config
                );
            }
        );
    }
}
