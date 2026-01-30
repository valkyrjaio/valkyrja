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

namespace Valkyrja\Application\Entry\Abstract;

use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Data\Data;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Kernel\Valkyrja;
use Valkyrja\Application\Throwable\Exception\RuntimeException;
use Valkyrja\Cli\Routing\Data\Data as CliData;
use Valkyrja\Container\Data\Data as ContainerData;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Event\Data\Data as EventData;
use Valkyrja\Http\Routing\Data\Data as HttpData;
use Valkyrja\Support\Directory\Directory;
use Valkyrja\Support\Time\Microtime;
use Valkyrja\Throwable\Handler\Contract\ThrowableHandlerContract;
use Valkyrja\Throwable\Handler\WhoopsThrowableHandler;

use function define;
use function defined;

abstract class App
{
    /**
     * Start the application.
     *
     * @param non-empty-string $dir The directory
     */
    public static function start(string $dir, Env $env): ApplicationContract
    {
        static::defaultExceptionHandler();
        static::appStart();
        static::directory(dir: $dir);

        return static::app(env: $env);
    }

    /**
     * Set a global constant for when the application as a whole started.
     */
    public static function appStart(): void
    {
        if (! defined('APP_START')) {
            define('APP_START', Microtime::get());
        }
    }

    /**
     * Let's set the base directory within the web server for our application
     * so that when we locate directories and files within the application
     * we have a standard location from which to do so.
     *
     * @param non-empty-string $dir The directory
     */
    public static function directory(string $dir): void
    {
        Directory::$BASE_PATH = $dir;
    }

    /**
     * Let's start up the application by creating a new instance of the
     * application class. This is going to bind all the various
     * components together into a singular hub. This will set the
     *  correct environment class file to use, and appropriate the config
     *  that should be loaded by the application. In dev you'll want to
     *  use the default config out of the root config directory, but
     *  when you're on a production environment definitely have
     *  your config cached and the flag set in your env class.
     */
    public static function app(Env $env): ApplicationContract
    {
        /** @var non-empty-string $cacheFilepath */
        $cacheFilepath = $env::APP_CACHE_FILE_PATH;
        $cacheFilename = Directory::basePath($cacheFilepath);

        if (is_file(filename: $cacheFilename)) {
            $configData = static::getData(cacheFilename: $cacheFilename);
        } else {
            $configData = static::getConfig();
        }

        $container = static::getContainer();

        return new Valkyrja(
            container: $container,
            env: $env,
            configData: $configData
        );
    }

    /**
     * Run the app.
     *
     * @param non-empty-string $dir The directory
     */
    abstract public static function run(string $dir, Env $env): void;

    /**
     * Get the application config.
     */
    protected static function getConfig(): Config
    {
        return new Config();
    }

    /**
     * Get the application data.
     *
     * @param non-empty-string $cacheFilename The cache file path
     */
    protected static function getData(string $cacheFilename): Data
    {
        $cache = file_get_contents(filename: $cacheFilename);

        if ($cache === false || $cache === '') {
            throw new RuntimeException('Error occurred when retrieving cache file contents');
        }

        /** @var mixed $data */
        $data = unserialize($cache, ['allowed_classes' => static::getAllowedDataClasses()]);

        if (! $data instanceof Data) {
            throw new RuntimeException('Invalid cache');
        }

        return $data;
    }

    /**
     * @return class-string[]
     */
    protected static function getAllowedDataClasses(): array
    {
        return [
            Data::class,
            CliData::class,
            ContainerData::class,
            EventData::class,
            HttpData::class,
        ];
    }

    /**
     * Set a default exception handler until the one specified in config is set in the Container\AppProvider.
     */
    protected static function defaultExceptionHandler(): void
    {
        WhoopsThrowableHandler::enable(
            displayErrors: true
        );
    }

    /**
     * Bootstrap throwable handler.
     */
    protected static function bootstrapThrowableHandler(ApplicationContract $app, ContainerContract $container): void
    {
        $errorHandler = static::getThrowableHandler();

        // Set error handler in the service container
        $container->setSingleton(ThrowableHandlerContract::class, $errorHandler);

        // If debug is on, enable debug handling
        if ($app->getDebugMode()) {
            // Enable error handling
            $errorHandler::enable(
                displayErrors: true
            );
        }
    }

    /**
     * Get the throwable handler.
     */
    protected static function getThrowableHandler(): ThrowableHandlerContract
    {
        return new WhoopsThrowableHandler();
    }

    /**
     * Get the container.
     */
    protected static function getContainer(): ContainerContract
    {
        return new Container();
    }
}
