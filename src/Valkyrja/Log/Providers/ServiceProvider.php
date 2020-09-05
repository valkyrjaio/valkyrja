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

namespace Valkyrja\Log\Providers;

use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as Monolog;
use Psr\Log\LoggerInterface;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Log\Adapters\NullAdapter;
use Valkyrja\Log\Adapters\PsrAdapter;
use Valkyrja\Log\Constants\LogLevel;
use Valkyrja\Log\Drivers\Driver;
use Valkyrja\Log\Logger;

use function date;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProvider extends Provider
{
    /**
     * What services are provided.
     *
     * @var array
     */
    public static array $provides = [
        Logger::class,
        Driver::class,
        NullAdapter::class,
        PsrAdapter::class,
        LoggerInterface::class,
    ];

    /**
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function publishers(): array
    {
        return [
            Logger::class          => 'publishLogger',
            Driver::class          => 'publishDefaultDriver',
            NullAdapter::class     => 'publishNullAdapter',
            PsrAdapter::class      => 'publishPsrAdapter',
            LoggerInterface::class => 'publishLoggerInterface',
        ];
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return static::$provides;
    }

    /**
     * Publish the provider.
     *
     * @param Container $container The container
     *
     * @throws Exception
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
    }

    /**
     * Bind the logger service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishLogger(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Logger::class,
            new \Valkyrja\Log\Managers\Logger(
                $container,
                $config['log']
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
     * Bind the psr adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishPsrAdapter(Container $container): void
    {
        $container->setClosure(
            PsrAdapter::class,
            static function (array $config) use ($container): PsrAdapter {
                return new PsrAdapter(
                    $container->get(
                        LoggerInterface::class,
                        [
                            $config,
                        ]
                    ),
                    $config
                );
            }
        );
    }

    /**
     * Bind the null adapter service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishNullAdapter(Container $container): void
    {
        $container->setClosure(
            NullAdapter::class,
            static function (array $config): NullAdapter {
                return new NullAdapter(
                    $config
                );
            }
        );
    }

    /**
     * Bind the logger interface.
     *
     * @param Container $container The container
     *
     * @throws Exception
     *
     * @return void
     */
    public static function publishLoggerInterface(Container $container): void
    {
        $container->setClosure(
            LoggerInterface::class,
            static function (array $config): LoggerInterface {
                $filePath = $config['filePath'];
                $name     = $config['name'] . date('-Y-m-d');

                return new Monolog(
                    $name,
                    [
                        new StreamHandler(
                            "${filePath}/${name}.log",
                            LogLevel::DEBUG
                        ),
                    ]
                );
            }
        );
    }
}
