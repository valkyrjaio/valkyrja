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
use Monolog\Logger as Monolog;
use Valkyrja\Container\CoreComponent;
use Valkyrja\Application;
use Valkyrja\Logger\LogLevel;
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
    public static $provides = [
        CoreComponent::LOGGER_INTERFACE,
        CoreComponent::LOGGER,
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
     * @param \Valkyrja\Application $app The application
     *
     * @throws \Exception
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
     * @param \Valkyrja\Application $app The application
     *
     * @throws \Exception
     *
     * @return void
     */
    protected static function bindLoggerInterface(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::LOGGER_INTERFACE,
            new Monolog(
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
     * @param \Valkyrja\Application $app The application
     *
     * @return void
     */
    protected static function bindLogger(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::LOGGER,
            new MonologLogger(
                $app->container()->getSingleton(CoreComponent::LOGGER_INTERFACE)
            )
        );
    }
}
