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

use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;
use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Contracts\Application;
use Valkyrja\Logger\Enums\LogLevel;
use Valkyrja\Logger\Logger;
use Valkyrja\Support\Provider;

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
    public static $provides = [
        CoreComponent::LOGGER_INTERFACE,
        CoreComponent::LOGGER,
    ];

    /**
     * Publish the provider.
     *
     * @param \Valkyrja\Contracts\Application $app The application
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
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    protected static function bindLoggerInterface(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::LOGGER_INTERFACE,
            new MonologLogger(
                $app->config()['logger']['name'],
                [
                    new StreamHandler(
                        $app->config()['logger']['filePath'],
                        LogLevel::DEBUG
                    ),
                ]
            )
        );
    }

    /**
     * Bind the logger.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    protected static function bindLogger(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::LOGGER,
            new Logger(
                $app->container()->getSingleton(CoreComponent::LOGGER_INTERFACE)
            )
        );
    }
}
