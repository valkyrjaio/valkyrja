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

use Valkyrja\Application\Config;
use Valkyrja\Application\Contract\Application;
use Valkyrja\Application\Data;
use Valkyrja\Application\Env;
use Valkyrja\Application\Exception\RuntimeException;
use Valkyrja\Application\Valkyrja;
use Valkyrja\Cli\Interaction\Factory\InputFactory;
use Valkyrja\Cli\Interaction\Input\Contract\Input;
use Valkyrja\Cli\Server\Contract\InputHandler;
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
     * @param non-empty-string $dir The directory
     */
    public static function start(string $dir, Env $env): Application
    {
        static::defaultErrorHandler();
        static::appStart();
        static::directory(dir: $dir);

        return static::app(env: $env);
    }

    /**
     * Now that the application has been bootstrapped and setup correctly with all our requirements lets run it!
     *
     * @param non-empty-string $dir The directory
     */
    public static function http(string $dir, Env $env): void
    {
        $app = static::start(
            dir: $dir,
            env: $env,
        );

        $container = $app->getContainer();

        self::bootstrapErrorHandler($app, $container);

        $handler = $container->getSingleton(RequestHandler::class);
        $handler->run(static::getRequest());
    }

    /**
     * Now that the application has been bootstrapped and setup correctly with all our requirements lets run it!
     *
     * @param non-empty-string $dir The directory
     */
    public static function cli(string $dir, Env $env): void
    {
        $app = static::start(
            dir: $dir,
            env: $env,
        );

        $container = $app->getContainer();

        self::bootstrapErrorHandler($app, $container);

        $handler = $container->getSingleton(InputHandler::class);
        $handler->run(static::getInput());
    }

    /**
     * Set a global constant for when the application as a whole started.
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
    public static function app(Env $env): Application
    {
        /** @var non-empty-string $cacheFilePath */
        $cacheFilePath = $env::APP_CACHE_FILE_PATH;

        if (is_file($cacheFilePath)) {
            $cache = file_get_contents($cacheFilePath);

            if ($cache === false || $cache === '') {
                throw new RuntimeException('Error occurred when retrieving cache file contents');
            }

            // Allow all classes, and filter for only Config classes down below since allowed_classes cannot be
            // a class that others extend off of, and we don't want to limit what a cached config class could be
            $data = unserialize($cache, ['allowed_classes' => true]);

            if (! $data instanceof Data) {
                throw new RuntimeException('Invalid cache');
            }

            $configData = $data;
        } else {
            $configData = static::getConfig();
        }

        return new Valkyrja(env: $env, configData: $configData);
    }

    /**
     * Get the application config.
     */
    protected static function getConfig(): Config
    {
        return new Config();
    }

    /**
     * Set a default error handler until the one specified in config is set in the Container\AppProvider.
     */
    protected static function defaultErrorHandler(): void
    {
        ErrorHandler::enable(
            displayErrors: true
        );
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
     * Get the request.
     */
    protected static function getRequest(): ServerRequest
    {
        return RequestFactory::fromGlobals();
    }

    /**
     * Get the input.
     */
    protected static function getInput(): Input
    {
        return InputFactory::fromGlobals();
    }
}
