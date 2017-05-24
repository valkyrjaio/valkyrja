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
use Valkyrja\Logger\Enums\LogLevel;
use Valkyrja\Logger\Logger;
use Valkyrja\Support\ServiceProvider;

/**
 * Class LoggerServiceProvider.
 *
 * @author Melech Mizrachi
 */
class LoggerServiceProvider extends ServiceProvider
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
     * Publish the service provider.
     *
     * @return void
     */
    public function publish(): void
    {
        $this->bindLoggerInterface();
        $this->bindLogger();
    }

    /**
     * Bind the logger interface.
     *
     * @return void
     */
    protected function bindLoggerInterface(): void
    {
        $this->app->container()->singleton(
            CoreComponent::LOGGER_INTERFACE,
            new MonologLogger(
                $this->app->config()['logger']['name'],
                [
                    new StreamHandler(
                        $this->app->config()['logger']['filePath'],
                        LogLevel::DEBUG
                    ),
                ]
            )
        );
    }

    /**
     * Bind the logger.
     *
     * @return void
     */
    protected function bindLogger(): void
    {
        $this->app->container()->singleton(
            CoreComponent::LOGGER,
            new Logger(
                $this->app->container()->get(CoreComponent::LOGGER_INTERFACE)
            )
        );
    }
}
