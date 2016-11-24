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
}
