<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts;

use Exception;

use Valkyrja\Contracts\Container\Container;
use Valkyrja\Contracts\Http\Response;
use Valkyrja\Contracts\Http\ResponseBuilder;
use Valkyrja\Contracts\Http\Router;
use Valkyrja\Contracts\View\View;

/**
 * Interface Application
 *
 * @package Valkyrja\Contracts
 *
 * @author  Melech Mizrachi
 */
interface Application
{
    /**
     * The Application framework version.
     *
     * @constant string
     */
    const VERSION = 'Valkyrja (1.0.0 Alpha)';

    /**
     * Application constructor.
     *
     * @param string $basePath The base path for the application
     */
    public function __construct($basePath);

    /**
     * Return the global $app variable.
     *
     * @return Application
     */
    public static function app() : Application;

    /**
     * Get the application version.
     *
     * @return string
     */
    public function version() : string;

    /**
     * Get the environment with which the application is running in.
     *
     * @return string
     */
    public function environment() : string;

    /**
     * Whether the application is running in debug mode or not.
     *
     * @return string
     */
    public function debug() : string;

    /**
     * Is twig enabled?
     *
     * @return bool
     */
    public function isTwigEnabled() : bool;

    /**
     * Set the timezone for the application process.
     *
     * @return void
     */
    public function setTimezone(); // : void;

    /**
     * Get whether the application is using a compiled version.
     *
     * @return bool
     */
    public function isCompiled() : bool;

    /**
     * Set the application as using compiled.
     *
     * @return void
     */
    public function setCompiled(); // : void;

    /**
     * Get a single environment variable via key or get all.
     *
     * @param string $key     [optional] The variable to get
     * @param mixed  $default [optional] Default value to return if not found
     *
     * @return mixed
     */
    public function env(string $key = null, $default = null); // : mixed;

    /**
     * Set a single environment variable.
     *
     * @param string $key   The key to set
     * @param mixed  $value The value to set
     *
     * @return Application
     */
    public function setEnv(string $key, $value) : Application;

    /**
     * Set all environment variables.
     *
     * @param array $env The environment variables to set
     *
     * @return void
     */
    public function setEnvs(array $env); // : void;

    /**
     * Get a single config variable via key or get all.
     *
     * @param string $key     [optional] The variable to get
     * @param mixed  $default [optional] Default value to return if not found
     *
     * @return mixed
     */
    public function config(string $key = null, $default = null); // : mixed;

    /**
     * Set a single config variable.
     *
     * @param string $key   The key to set
     * @param mixed  $value The value to set
     *
     * @return Application
     */
    public function setConfig(string $key, $value) : Application;

    /**
     * Set all config variables.
     *
     * @param array $config The environment variables to set
     *
     * @return void
     */
    public function setConfigVars(array $config); // : void;

    /**
     * Get the base directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function basePath(string $path = null) : string;

    /**
     * Get the app directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function appPath(string $path = null) : string;

    /**
     * Get the cache directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function cachePath(string $path = null) : string;

    /**
     * Get the config directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function configPath(string $path = null) : string;

    /**
     * Get the framework directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function frameworkPath(string $path = null) : string;

    /**
     * Get the public directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function publicPath(string $path = null) : string;

    /**
     * Get the resources directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function resourcesPath(string $path = null) : string;

    /**
     * Get the storage directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function storagePath(string $path = null) : string;

    /**
     * Get the tests directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function testsPath(string $path = null) : string;

    /**
     * Get the vendor directory for the application.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function vendorPath(string $path = null) : string;

    /**
     * Convert a PHP error to an ErrorException.
     *
     * @param int    $level   The error level
     * @param string $message The error message
     * @param string $file    [optional] The file within which the error occurred
     * @param int    $line    [optional] The line which threw the error
     * @param array  $context [optional] The context for the exception
     *
     * @return void
     *
     * @throws \Exception
     */
    public function handleError($level, $message, $file = '', $line = 0, $context = []); // : void;

    /**
     * Handle an uncaught exception from the application.
     *
     * Note: Most exceptions can be handled via the try / catch block in
     * the HTTP and Console kernels. But, fatal error exceptions must
     * be handled differently since they are not normal exceptions.
     *
     * @param \Throwable $e The exception that was captured
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function handleException($e) : Response;

    /**
     * Handle the PHP shutdown event.
     *
     * @return void
     */
    public function handleShutdown(); // : void;

    /**
     * Throw an http exception.
     *
     * @param int        $statusCode The status code to use
     * @param string     $message    [optional] The Exception message to throw
     * @param \Exception $previous   [optional] The previous exception used for the exception chaining
     * @param array      $headers    [optional] The headers to send
     * @param string     $view       [optional] The view template name to use
     * @param int        $code       [optional] The Exception code
     *
     * @return void
     *
     * @throws \Valkyrja\Contracts\Exceptions\HttpException
     */
    public function httpException(
        $statusCode,
        $message = null,
        Exception $previous = null,
        array $headers = [],
        $view = null,
        $code = 0
    ); // : void;

    /**
     * Abort the application due to error.
     *
     * @param int    $code    [optional] The status code to use
     * @param string $message [optional] The message or data content to use
     * @param array  $headers [optional] The headers to set
     * @param string $view    [optional] The view template name to use
     *
     * @return void
     *
     * @throws \Valkyrja\Contracts\Exceptions\HttpException
     */
    public function abort(int $code = 404, string $message = '', array $headers = [], string $view = null); // : void;

    /**
     * Return a new response from the application.
     *
     * @param string $content [optional] The content to set
     * @param int    $status  [optional] The status code to set
     * @param array  $headers [optional] The headers to set
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function response(string $content = '', int $status = 200, array $headers = []) : Response;

    /**
     * Return a new response builder from the application.
     *
     * @return \Valkyrja\Contracts\Http\ResponseBuilder
     */
    public function responseBuilder() : ResponseBuilder;

    /**
     * Return the router instance from the container.
     *
     * @return \Valkyrja\Contracts\Http\Router
     */
    public function router() : Router;

    /**
     * Return a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function view(string $template = '', array $variables = []) : View;

    /**
     * Run the application.
     *
     * @return void
     */
    public function run(); // : void;

    /**
     * Register a service provider.
     *
     * @param string $serviceProvider The service provider
     *
     * @return void
     */
    public function register(string $serviceProvider); // : void;

    /**
     * Set the service container for dependency injection.
     *
     * @return \Valkyrja\Contracts\Container\Container
     */
    public function container() : Container;

    /**
     * Set the service container for dependency injection.
     *
     * @param string               $abstract The abstract to use as the key
     * @param \Closure|array|mixed $instance The instance to set
     *
     * @return void
     */
    public function instance(string $abstract, $instance); // : void;
}
