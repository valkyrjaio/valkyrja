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

use Valkyrja\Application\Data\CliConfig;
use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Kernel\Valkyrja;
use Valkyrja\Container\Data\Data;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\ServiceProvider;
use Valkyrja\Support\Time\Microtime;
use Valkyrja\Throwable\Handler\Contract\ThrowableHandlerContract;
use Valkyrja\Throwable\Handler\WhoopsThrowableHandler;

use function define;
use function defined;

abstract class App
{
    /**
     * Start the application.
     */
    public static function start(Env $env, Config $config): ApplicationContract
    {
        if ($config->debugMode) {
            static::defaultExceptionHandler();
        }

        static::appStart();
        static::directory(dir: $config->dir);

        return static::app(env: $env, config: $config);
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
        Directory::$basePath = $dir;
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
    public static function app(Env $env, Config $config): ApplicationContract
    {
        $container = static::getContainer();
        $app       = static::getApplication(container: $container, config: $config);

        static::bootstrapServices(
            app: $app,
            container: $container,
            env: $env,
            config: $config
        );

        return $app;
    }

    /**
     * Run the app.
     */
    abstract public static function run(Config $config, Env $env = new Env()): void;

    /**
     * Get the application.
     */
    protected static function getApplication(ContainerContract $container, Config $config): ApplicationContract
    {
        return new Valkyrja(
            container: $container,
            config: $config,
        );
    }

    /**
     * Bootstrap container services.
     */
    protected static function bootstrapServices(ApplicationContract $app, ContainerContract $container, Env $env, Config $config): void
    {
        $container->setSingleton(Env::class, $env);
        $container->setSingleton(Config::class, $config);
        $container->setSingleton($config::class, $config);
        $container->setSingleton(ContainerContract::class, $container);
        $container->setSingleton(ApplicationContract::class, $app);

        if ($config instanceof CliConfig) {
            $container->setSingleton(CliConfig::class, $config);
            $container->setSingleton(HttpConfig::class, $config->http);
        }

        if ($config instanceof HttpConfig) {
            $container->setSingleton(HttpConfig::class, $config);
        }

        $app->publishProviderCallbacks();

        static::loadContainerData(container: $container);
    }

    /**
     * Load container data.
     */
    protected static function loadContainerData(ContainerContract $container): void
    {
        if (! $container->isSingleton(Data::class)) {
            self::publishContainerData(container: $container);
        }

        $containerData = $container->getSingleton(Data::class);

        $container->setFromData($containerData);
    }

    /**
     * Publish the container data.
     */
    protected static function publishContainerData(ContainerContract $container): void
    {
        ServiceProvider::publishData(container: $container);
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
        // If debug is on, enable debug handling
        if ($app->getDebugMode()) {
            $errorHandler = static::getThrowableHandler();

            // Set error handler in the service container
            $container->setSingleton(ThrowableHandlerContract::class, $errorHandler);

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
