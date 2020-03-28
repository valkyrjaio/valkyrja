<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Log\Providers;

use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as Monolog;
use Psr\Log\LoggerInterface;
use Valkyrja\Application\Application;
use Valkyrja\Log\Enums\LogLevel;
use Valkyrja\Log\Logger;
use Valkyrja\Log\Loggers\MonologLogger;
use Valkyrja\Support\Providers\Provider;

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
        $config  = $app->config()['log'];
        $handler = new StreamHandler(
            $config['filePath'] . '/' . $config['name'] . date('-Y-m-d') . '.log',
            LogLevel::DEBUG
        );

        $app->container()->setSingleton(
            LoggerInterface::class,
            new Monolog(
                $config['name'] . date('-Y-m-d'),
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
        $app->container()->setSingleton(
            Logger::class,
            new MonologLogger(
                $app->container()->getSingleton(LoggerInterface::class)
            )
        );
    }
}
