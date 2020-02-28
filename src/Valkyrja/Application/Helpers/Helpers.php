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

namespace Valkyrja\Application\Helpers;

use Valkyrja\Application\Application;
use Valkyrja\Config\Config;
use Valkyrja\Config\Enums\ConfigKeyPart;
use Valkyrja\Config\Models\ConfigModel as ConfigModel;
use Valkyrja\Container\Container;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Env\Env;
use Valkyrja\Event\Events;
use Valkyrja\Exception\ExceptionHandler;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\Exceptions\HttpRedirectException;
use Valkyrja\Http\Response;

use function constant;
use function defined;

/**
 * Trait Helpers.
 *
 * @author Melech Mizrachi
 *
 * @property Application      $app
 * @property string           $env
 * @property Config           $config
 * @property Container        $container
 * @property Dispatcher       $dispatcher
 * @property Events           $events
 * @property ExceptionHandler $exceptionHandler
 */
trait Helpers
{
    /**
     * Get the application instance.
     *
     * @return Application
     */
    public static function app(): Application
    {
        return self::$app;
    }

    /**
     * Get an environment variable.
     *
     * @param string $key     [optional] The variable to get
     * @param mixed  $default [optional] The default value to return
     *
     * @return mixed
     */
    public static function env(string $key = null, $default = null)
    {
        // If there was no variable requested
        if (null === $key) {
            // Return the env class
            return static::getEnv();
        }

        // If the env has this variable defined and the variable isn't null
        if (
            defined(static::getEnv() . '::' . $key)
            && null !== $env = constant(static::getEnv() . '::' . $key)
        ) {
            // Return the variable
            return $env;
        }

        // Otherwise return the default
        return $default;
    }

    /**
     * Get the environment variables class.
     *
     * @return string
     */
    public static function getEnv(): string
    {
        return self::$env ?? (self::$env = Env::class);
    }

    /**
     * Set the environment variables class.
     *
     * @param string $env [optional] The env file to use
     *
     * @return void
     */
    public static function setEnv(string $env = null): void
    {
        // Set the env class to use
        self::$env = ($env ?? self::$env ?? Env::class);
    }

    /**
     * Get the config.
     *
     * @param string $key     [optional] The key to get
     * @param mixed  $default [optional] The default value if the key is not found
     *
     * @return mixed|Config|null
     */
    public function config(string $key = null, $default = null)
    {
        // If no key was specified
        if (null === $key) {
            // Return all the entire config
            return self::$config;
        }

        // Explode the keys on period
        $keys = explode(ConfigKeyPart::SEP, $key);
        // Set the config to return
        $config = self::$config;

        // Iterate through the keys
        foreach ($keys as $configItem) {
            // Trying to get the item from the config
            // or load the default
            $config = $config->{$configItem} ?? $default;

            // If the item was not found, might as well return out from here
            // instead of continuing to iterate through the remaining keys
            if ($default === $config) {
                return $default;
            }
        }

        // do while($current !== $default);

        // Return the found config
        return $config;
    }

    /**
     * Add to the global config array.
     *
     * @param Config $newConfig The new config to add
     * @param string $key       The key to use
     *
     * @return void
     */
    public function addConfig(Config $newConfig, string $key): void
    {
        // Set the config within the application
        self::$config->{$key} = $newConfig;
    }

    /**
     * Get the container instance.
     *
     * @return Container
     */
    public function container(): Container
    {
        return self::$container;
    }

    /**
     * Get the dispatcher instance.
     *
     * @return Dispatcher
     */
    public function dispatcher(): Dispatcher
    {
        return self::$dispatcher;
    }

    /**
     * Get the events instance.
     *
     * @return Events
     */
    public function events(): Events
    {
        return self::$events;
    }

    /**
     * Get the exception handler instance.
     *
     * @return ExceptionHandler
     */
    public function exceptionHandler(): ExceptionHandler
    {
        return self::$exceptionHandler;
    }

    /**
     * Get the environment with which the application is running in.
     *
     * @return string
     */
    public function environment(): string
    {
        return self::$config->app->env;
    }

    /**
     * Whether the application is running in debug mode or not.
     *
     * @return bool
     */
    public function debug(): bool
    {
        return self::$config->app->debug;
    }

    /**
     * Abort the application due to error.
     *
     * @param int      $statusCode The status code to use
     * @param string   $message    [optional] The Exception message to throw
     * @param array    $headers    [optional] The headers to send
     * @param int      $code       [optional] The Exception code
     * @param Response $response   [optional] The Response to send
     *
     * @throws HttpException
     *
     * @return void
     */
    public function abort(
        int $statusCode = StatusCode::NOT_FOUND,
        string $message = '',
        array $headers = [],
        int $code = 0,
        Response $response = null
    ): void {
        throw new self::$config->app->httpException(
            $statusCode,
            $message,
            null,
            $headers,
            $code,
            $response
        );
    }

    /**
     * Redirect to a given uri, and abort the application.
     *
     * @param string $uri        [optional] The URI to redirect to
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @throws HttpRedirectException
     *
     * @return void
     */
    public function redirectTo(string $uri = null, int $statusCode = StatusCode::FOUND, array $headers = []): void
    {
        throw new HttpRedirectException($statusCode, $uri, null, $headers, 0);
    }
}
