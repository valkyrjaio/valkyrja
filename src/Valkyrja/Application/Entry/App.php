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

use Valkyrja\Application\Config\Valkyrja as ValkyrjaConfig;
use Valkyrja\Application\Contract\Application;
use Valkyrja\Application\Env;
use Valkyrja\Application\Valkyrja;
use Valkyrja\Cli\Interaction\Factory\InputFactory;
use Valkyrja\Cli\Interaction\Input\Contract\Input;
use Valkyrja\Cli\Server\Contract\InputHandler;
use Valkyrja\Exception\ErrorHandler;
use Valkyrja\Http\Message\Factory\RequestFactory;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Server\Contract\RequestHandler;
use Valkyrja\Support\Directory;

use function define;

/**
 * Class App.
 *
 * @author Melech Mizrachi
 */
abstract class App
{
    /**
     * Start the application.
     *
     * @param string                       $dir        The directory
     * @param class-string<Env>            $env        The env class to use
     * @param class-string<ValkyrjaConfig> $dataConfig The config class to use
     *
     * @return Application
     */
    public static function start(string $dir, string $env, string $dataConfig): Application
    {
        static::defaultErrorHandler(
            dir: $dir,
            env: $env,
            dataConfig: $dataConfig
        );

        static::appStart(
            dir: $dir,
            env: $env,
            dataConfig: $dataConfig
        );
        static::directory(
            dir: $dir,
            env: $env,
            dataConfig: $dataConfig
        );
        static::env(
            dir: $dir,
            env: $env,
            dataConfig: $dataConfig
        );

        return static::app(
            dir: $dir,
            env: $env,
            dataConfig: $dataConfig
        );
    }

    /**
     * Now that the application has been bootstrapped and setup correctly with all our requirements lets run it!
     *
     * @param string                       $dir        The directory
     * @param class-string<Env>            $env        The env class to use
     * @param class-string<ValkyrjaConfig> $dataConfig The config class to use
     *
     * @return void
     */
    public static function http(string $dir, string $env, string $dataConfig): void
    {
        $app = static::start(
            dir: $dir,
            env: $env,
            dataConfig: $dataConfig
        );

        $handler = $app->getContainer()->getSingleton(RequestHandler::class);
        $handler->run(static::getRequest());
    }

    /**
     * Now that the application has been bootstrapped and setup correctly with all our requirements lets run it!
     *
     * @param string                       $dir        The directory
     * @param class-string<Env>            $env        The env class to use
     * @param class-string<ValkyrjaConfig> $dataConfig The config class to use
     *
     * @return void
     */
    public static function cli(string $dir, string $env, string $dataConfig): void
    {
        $app = static::start(
            dir: $dir,
            env: $env,
            dataConfig: $dataConfig
        );

        $handler = $app->getContainer()->getSingleton(InputHandler::class);
        $handler->run(static::getInput());
    }

    /**
     * Set a default error handler until the one specified in config is set in the Container\AppProvider.
     *
     * @param string                       $dir        The directory
     * @param class-string<Env>            $env        The env class to use
     * @param class-string<ValkyrjaConfig> $dataConfig The config class to use
     *
     * @return void
     */
    protected static function defaultErrorHandler(string $dir, string $env, string $dataConfig): void
    {
        ErrorHandler::enable(
            displayErrors: true
        );
    }

    /**
     * Set a global constant for when the application as a whole started.
     *
     * @param string                       $dir        The directory
     * @param class-string<Env>            $env        The env class to use
     * @param class-string<ValkyrjaConfig> $dataConfig The config class to use
     *
     * @return void
     */
    protected static function appStart(string $dir, string $env, string $dataConfig): void
    {
        define('APP_START', microtime(true));
    }

    /**
     * Let's set the base directory within the web server for our application
     * so that when we locate directories and files within the application
     * we have a standard location from which to do so.
     *
     * @param string                       $dir        The directory
     * @param class-string<Env>            $env        The env class to use
     * @param class-string<ValkyrjaConfig> $dataConfig The config class to use
     *
     * @return void
     */
    protected static function directory(string $dir, string $env, string $dataConfig): void
    {
        Directory::$BASE_PATH = $dir;
    }

    /**
     * Let's setup the application by bootstrapping it. This will set the
     * correct environment class file to use, and appropriate the config
     * that should be loaded by the application. In dev you'll want to
     * use the default config out of the root config directory, but
     * when you're on a production environment definitely have
     * your config cached and the flag set in your env class.
     *
     * @param string                       $dir        The directory
     * @param class-string<Env>            $env        The env class to use
     * @param class-string<ValkyrjaConfig> $dataConfig The config class to use
     *
     * @return void
     */
    protected static function env(string $dir, string $env, string $dataConfig): void
    {
        Valkyrja::setEnv($env);
    }

    /**
     * Let's start up the application by creating a new instance of the
     * application class. This is going to bind all the various
     * components together into a singular hub.
     *
     * @param string                       $dir        The directory
     * @param class-string<Env>            $env        The env class to use
     * @param class-string<ValkyrjaConfig> $dataConfig The config class to use
     *
     * @return Application
     */
    protected static function app(string $dir, string $env, string $dataConfig): Application
    {
        return new Valkyrja(dataConfig: $dataConfig);
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
