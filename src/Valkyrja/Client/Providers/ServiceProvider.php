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

namespace Valkyrja\Client\Providers;

use GuzzleHttp\Client as Guzzle;
use Valkyrja\Client\Adapter;
use Valkyrja\Client\Client;
use Valkyrja\Client\Driver;
use Valkyrja\Client\GuzzleAdapter;
use Valkyrja\Client\Loader;
use Valkyrja\Client\Loaders\ContainerLoader;
use Valkyrja\Client\LogAdapter;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Log\Logger;

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
            Client::class        => 'publishClient',
            Loader::class        => 'publishLoader',
            Driver::class        => 'publishDriver',
            GuzzleAdapter::class => 'publishGuzzleAdapter',
            LogAdapter::class    => 'publishLogAdapter',
            Adapter::class       => 'publishAdapter',
        ];
    }

    /**
     * @inheritDoc
     */
    public static function provides(): array
    {
        return [
            Client::class,
            Loader::class,
            Driver::class,
            GuzzleAdapter::class,
            LogAdapter::class,
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
     * Publish the client service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishClient(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Client::class,
            new \Valkyrja\Client\Managers\Client(
                $container->getSingleton(Loader::class),
                $config['client']
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
            static function (string $name, Adapter $adapter): Driver {
                return new $name($adapter);
            }
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
            static function (string $name, array $config) use ($responseFactory): GuzzleAdapter {
                return new $name(
                    new Guzzle($config['options'] ?? null),
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
            static function (string $name, array $config) use ($responseFactory): Adapter {
                return new $name(
                    $responseFactory,
                    $config
                );
            }
        );
    }
}
