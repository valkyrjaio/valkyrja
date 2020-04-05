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
use Valkyrja\Log\Enums\LogLevel;
use Valkyrja\Log\Logger;
use Valkyrja\Log\Loggers\MonologLogger;

use function date;

/**
 * Class LoggerServiceProvider.
 *
 * @author Melech Mizrachi
 */
class LoggerServiceProvider extends Provider
{
    /**
     * What services are provided.
     *
     * @var array
     */
    public static array $provides = [
        LoggerInterface::class,
        Logger::class,
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
        static::bindLoggerInterface($container);
        static::bindLogger($container);
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
    protected static function bindLoggerInterface(Container $container): void
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
     * Bind the logger.
     *
     * @param Container $container The container
     *
     * @return void
     */
    protected static function bindLogger(Container $container): void
    {
        $container->setSingleton(
            Logger::class,
            new MonologLogger(
                $container->getSingleton(LoggerInterface::class)
            )
        );
    }
}
