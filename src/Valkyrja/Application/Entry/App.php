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

namespace Valkyrja\Application\Entry;

use Valkyrja\Application\Config\ValkyrjaConfig;
use Valkyrja\Application\Contract\Application;
use Valkyrja\Application\Env;
use Valkyrja\Application\Valkyrja;
use Valkyrja\Cli\Interaction\Factory\InputFactory;
use Valkyrja\Cli\Interaction\Input\Contract\Input;
use Valkyrja\Cli\Server\Contract\InputHandler;
use Valkyrja\Container\CacheableContainer;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Exception\Contract\ErrorHandler as ErrorHandlerContract;
use Valkyrja\Exception\ErrorHandler;
use Valkyrja\Http\Message\Factory\RequestFactory;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Server\Contract\RequestHandler;
use Valkyrja\Support\Directory;

use function define;

/**
 * Abstract Class App.
 *
 * @author Melech Mizrachi
 */
abstract class App
{
    /**
     * Start the application.
     *
     * @param string                       $dir    The directory
     * @param class-string<Env>            $env    The env class to use
     * @param class-string<ValkyrjaConfig> $config The config class to use
     *
     * @return Application
     */
    public static function start(string $dir, string $env, string $config): Application
    {
        static::defaultErrorHandler();
        static::appStart();
        static::directory(dir: $dir);

        return static::app(env: $env, config: $config);
    }

    /**
     * Now that the application has been bootstrapped and setup correctly with all our requirements lets run it!
     *
     * @param string                       $dir    The directory
     * @param class-string<Env>            $env    The env class to use
     * @param class-string<ValkyrjaConfig> $config The config class to use
     *
     * @return void
     */
    public static function http(string $dir, string $env, string $config): void
    {
        $app = static::start(
            dir: $dir,
            env: $env,
            config: $config
        );

        $container = static::getContainer($app);

        $handler = $container->getSingleton(RequestHandler::class);
        $handler->run(static::getRequest());
    }

    /**
     * Now that the application has been bootstrapped and setup correctly with all our requirements lets run it!
     *
     * @param string                       $dir    The directory
     * @param class-string<Env>            $env    The env class to use
     * @param class-string<ValkyrjaConfig> $config The config class to use
     *
     * @return void
     */
    public static function cli(string $dir, string $env, string $config): void
    {
        $app = static::start(
            dir: $dir,
            env: $env,
            config: $config
        );

        $container = static::getContainer($app);

        $handler = $container->getSingleton(InputHandler::class);
        $handler->run(static::getInput());
    }

    /**
     * Get the container.
     */
    public static function getContainer(Application $app): Container
    {
        $config = $app->getConfig();

        $container = new CacheableContainer($config->container);

        $app->setContainer($container);

        self::bootstrapServices($app, $container);

        $container->setup();

        // Bootstrap debug capabilities
        self::bootstrapErrorHandler($app, $container);
        // Bootstrap the timezone
        self::bootstrapTimezone($config);

        return $container;
    }

    /**
     * Set a global constant for when the application as a whole started.
     *
     * @return void
     */
    public static function appStart(): void
    {
        define('APP_START', microtime(true));
    }

    /**
     * Let's set the base directory within the web server for our application
     * so that when we locate directories and files within the application
     * we have a standard location from which to do so.
     *
     * @param string $dir The directory
     *
     * @return void
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
     *
     * @param class-string<Env>            $env    The env class to use
     * @param class-string<ValkyrjaConfig> $config The config class to use
     *
     * @return Application
     */
    public static function app(string $env, string $config): Application
    {
        return new Valkyrja(env: $env, config: $config);
    }

    /**
     * Bootstrap container services.
     */
    protected static function bootstrapServices(Application $app, Container $container): void
    {
        $env = $app->getEnv();

        $container->setSingleton(Application::class, $app);
        $container->setSingleton(Env::class, new $env());
        $container->setSingleton(ValkyrjaConfig::class, $app->getConfig());
        $container->setSingleton(Container::class, $container);
    }

    /**
     * Bootstrap error handler.
     */
    protected static function bootstrapErrorHandler(Application $app, Container $container): void
    {
        $errorHandler = new ErrorHandler();

        // Set error handler in the service container
        $container->setSingleton(ErrorHandlerContract::class, $errorHandler);

        // If debug is on, enable debug handling
        if ($app->getDebugMode()) {
            // Enable error handling
            $errorHandler::enable(
                displayErrors: true
            );
        }
    }

    /**
     * Bootstrap the timezone.
     */
    protected static function bootstrapTimezone(ValkyrjaConfig $config): void
    {
        date_default_timezone_set($config->app->timezone);
    }

    /**
     * Set a default error handler until the one specified in config is set in the Container\AppProvider.
     *
     * @return void
     */
    protected static function defaultErrorHandler(): void
    {
        ErrorHandler::enable(
            displayErrors: true
        );
    }

    /**
     * Get the request.
     *
     * @return ServerRequest
     */
    protected static function getRequest(): ServerRequest
    {
        return RequestFactory::fromGlobals();
    }

    /**
     * Get the input.
     *
     * @return Input
     */
    protected static function getInput(): Input
    {
        return InputFactory::fromGlobals();
    }
}
