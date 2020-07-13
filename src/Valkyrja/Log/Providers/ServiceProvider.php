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
use Valkyrja\Log\Adapters\PsrAdapter;
use Valkyrja\Log\Constants\LogLevel;
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
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function publishers(): array
    {
        return [
            LoggerInterface::class => 'publishLoggerInterface',
            Logger::class          => 'publishLogger',
            PsrAdapter::class      => 'publishPsrAdapter',
        ];
    }

    /**
     * What services are provided.
     *
     * @var array
     */
    public static array $provides = [
        LoggerInterface::class,
        Logger::class,
        PsrAdapter::class,
    ];

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
        $config    = $container->getSingleton('config');
        $logConfig = $config['log'];
        $handler   = new StreamHandler(
            $logConfig['filePath'] . '/' . $logConfig['name'] . date('-Y-m-d') . '.log',
            LogLevel::DEBUG
        );

        $container->setSingleton(
            LoggerInterface::class,
            new Monolog(
                $logConfig['name'] . date('-Y-m-d'),
                [
                    $handler,
                ]
            )
        );
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
            new \Valkyrja\Log\Loggers\Logger(
                $container,
                (array) $config['log']
            )
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
        $container->setSingleton(
            PsrAdapter::class,
            new PsrAdapter(
                $container->getSingleton(LoggerInterface::class)
            )
        );
    }
}
