<?php

namespace Valkyrja\Logger\Providers;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;
use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Container\Service;
use Valkyrja\Dispatcher\Dispatch;
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
        StreamHandler::class,
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
        $this->app->container()->bind(
            (new Service())
                ->setSingleton(true)
                ->setId(StreamHandler::class)
                ->setClass(StreamHandler::class)
                ->setArguments([
                    $this->app->config()->logger->filePath,
                    LogLevel::DEBUG,
                ])
        );

        $this->app->container()->bind(
            (new Service())
                ->setSingleton(true)
                ->setId(CoreComponent::LOGGER_INTERFACE)
                ->setClass(MonologLogger::class)
                ->setArguments([
                    $this->app->config()->logger->name,
                    (new Dispatch())
                        ->setClass(static::class)
                        ->setMethod('getLoggerHandlers')
                        ->setStatic(true),
                ])
        );
    }

    /**
     * Get the monolog arguments.
     *
     * @return array
     */
    public static function getLoggerHandlers(): array
    {
        return [
            container()->get(StreamHandler::class),
        ];
    }

    /**
     * Bind the logger.
     *
     * @return void
     */
    protected function bindLogger(): void
    {
        $this->app->container()->bind(
            (new Service())
                ->setSingleton(true)
                ->setId(CoreComponent::LOGGER)
                ->setClass(Logger::class)
                ->setDependencies([CoreComponent::LOGGER_INTERFACE])
        );
    }
}
