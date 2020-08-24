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
use Valkyrja\Client\Adapters\GuzzleAdapter;
use Valkyrja\Client\Adapters\LogAdapter;
use Valkyrja\Client\Adapters\NullAdapter;
use Valkyrja\Client\Client;
use Valkyrja\Client\Drivers\Driver;
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
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function publishers(): array
    {
        return [
            Client::class        => 'publishClient',
            Driver::class        => 'publishDefaultDriver',
            GuzzleAdapter::class => 'publishGuzzleAdapter',
            LogAdapter::class    => 'publishLogAdapter',
            NullAdapter::class   => 'publishNullAdapter',
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
            Client::class,
            Driver::class,
            GuzzleAdapter::class,
            LogAdapter::class,
            NullAdapter::class,
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
                $container,
                $config['client']
            )
        );
    }

    /**
     * Publish the default driver service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishDefaultDriver(Container $container): void
    {
        $container->setClosure(
            Driver::class,
            static function (array $config, string $adapter) use ($container): Driver {
                return new Driver(
                    $container->get(
                        $adapter,
                        [
                            $config,
                        ]
                    )
                );
            }
        );
    }

    /**
     * Publish the guzzle adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishGuzzleAdapter(Container $container): void
    {
        /** @var ResponseFactory $responseFactory */
        $responseFactory = $container->getSingleton(ResponseFactory::class);

        $container->setClosure(
            GuzzleAdapter::class,
            static function (array $config) use ($responseFactory): GuzzleAdapter {
                return new GuzzleAdapter(
                    new Guzzle(),
                    $responseFactory,
                    $config
                );
            }
        );
    }

    /**
     * Publish the log adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishLogAdapter(Container $container): void
    {
        /** @var Logger $logger */
        $logger = $container->getSingleton(Logger::class);
        /** @var ResponseFactory $responseFactory */
        $responseFactory = $container->getSingleton(ResponseFactory::class);

        $container->setClosure(
            LogAdapter::class,
            static function (array $config) use ($logger, $responseFactory): LogAdapter {
                return new LogAdapter(
                    $logger,
                    $responseFactory,
                    $config
                );
            }
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
        /** @var ResponseFactory $responseFactory */
        $responseFactory = $container->getSingleton(ResponseFactory::class);

        $container->setClosure(
            NullAdapter::class,
            static function (array $config) use ($responseFactory): NullAdapter {
                return new NullAdapter(
                    $responseFactory,
                    $config
                );
            }
        );
    }
}
