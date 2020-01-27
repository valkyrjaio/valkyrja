<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Logger\Providers;

use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as Monolog;
use Psr\Log\LoggerInterface;
use Valkyrja\Application;
use Valkyrja\Logger\Enums\LogLevel;
use Valkyrja\Logger\Logger;
use Valkyrja\Logger\MonologLogger;
use Valkyrja\Support\Providers\Provider;

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
     * @param Application $app The application
     *
     * @throws Exception
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        static::bindLoggerInterface($app);
        static::bindLogger($app);
    }

    /**
     * Bind the logger interface.
     *
     * @param Application $app The application
     *
     * @throws Exception
     *
     * @return void
     */
    protected static function bindLoggerInterface(Application $app): void
    {
        $handler = new StreamHandler(
            $app->config()['logger']['filePath'],
            LogLevel::DEBUG
        );

        $app->container()->singleton(
            LoggerInterface::class,
            new Monolog(
                $app->config()['logger']['name'],
                [
                    $handler,
                ]
            )
        );
    }

    /**
     * Bind the logger.
     *
     * @param Application $app The application
     *
     * @return void
     */
    protected static function bindLogger(Application $app): void
    {
        $app->container()->singleton(
            Logger::class,
            new MonologLogger(
                $app->container()->getSingleton(LoggerInterface::class)
            )
        );
    }
}
