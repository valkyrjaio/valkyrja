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

use Valkyrja\Application\Constant\ApplicationInfo;
use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Kernel\Valkyrja;
use Valkyrja\Application\Provider\Provider;
use Valkyrja\Container\Data\Data;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\ServiceProvider;
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
        $container = static::getContainer();
        $app       = static::getApplication(container: $container, env: $env);

        static::bootstrapServices(
            app: $app,
            container: $container,
            env: $env,
        );

        return $app;
    }

    /**
     * Run the app.
     *
     * @param non-empty-string $dir The directory
     */
    abstract public static function run(string $dir, Env $env): void;

    /**
     * Get the application.
     */
    protected static function getApplication(ContainerContract $container, Env $env): ApplicationContract
    {
        $config = static::getConfig(env: $env);

        return new Valkyrja(
            container: $container,
            config: $config,
        );
    }

    /**
     * Bootstrap container services.
     */
    protected static function bootstrapServices(ApplicationContract $app, ContainerContract $container, Env $env): void
    {
        $container->setSingleton(Env::class, $env);
        $container->setSingleton(ContainerContract::class, $container);
        $container->setSingleton(ApplicationContract::class, $app);

        static::loadContainerData(container: $container);
    }

    /**
     * Load container data.
     */
    protected static function loadContainerData(ContainerContract $container): void
    {
        ServiceProvider::publishData(container: $container);
        $containerData = $container->getSingleton(Data::class);

        $container->setFromData($containerData);
    }

    /**
     * Get the application config.
     */
    protected static function getConfig(Env $env): Config
    {
        $providers = static::getProviders(env: $env);
        /** @var non-empty-string $timezone */
        $timezone = $env::APP_TIMEZONE;
        /** @var non-empty-string $version */
        $version = $env::APP_VERSION
            ?? ApplicationInfo::VERSION;
        /** @var bool $debugMode */
        $debugMode = $env::APP_DEBUG_MODE;
        /** @var non-empty-string $environment */
        $environment = $env::APP_ENVIRONMENT;

        return new Config(
            version: $version,
            environment: $environment,
            debugMode: $debugMode,
            timezone: $timezone,
            providers: $providers,
        );
    }

    /**
     * Get the providers to register.
     *
     * @return class-string<Provider>[]
     */
    protected static function getProviders(Env $env): array
    {
        /** @var class-string<Provider>[] $requiredComponents */
        $requiredComponents = $env::APP_REQUIRED_COMPONENTS;
        /** @var class-string<Provider>[] $coreComponents */
        $coreComponents = $env::APP_CORE_COMPONENTS;
        /** @var class-string<Provider>[] $components */
        $components = $env::APP_COMPONENTS;
        /** @var class-string<Provider>[] $customComponents */
        $customComponents = $env::APP_CUSTOM_COMPONENTS;

        return array_merge($requiredComponents, $coreComponents, $components, $customComponents);
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
