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

use Valkyrja\Contracts\Config\Config;
use Valkyrja\Contracts\Config\Env;
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
     * Get the application instance.
     *
     * @return \Valkyrja\Contracts\Application
     */
    public static function app() : Application;

    /**
     * Get the application version.
     *
     * @return string
     */
    public function version() : string;

    /**
     * Get the container instance.
     *
     * @return \Valkyrja\Contracts\Container\Container
     */
    public function container() : Container;

    /**
     * Get the config class instance.
     *
     * @return \Valkyrja\Contracts\Config\Config|\Valkyrja\Config\Config|\config\Config
     */
    public function config() : Config;

    /**
     * Get environment variables.
     *
     * @return \Valkyrja\Contracts\Config\Env|\Valkyrja\Config\Env||config|Env
     */
    public function env() : Env;

    /**
     * Return the router instance from the container.
     *
     * @return \Valkyrja\Contracts\Http\Router
     */
    public function router() : Router;

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
     * Return a new response from the application.
     *
     * @return \Valkyrja\Contracts\Http\ResponseBuilder
     */
    public function responseBuilder() : ResponseBuilder;

    /**
     * Helper function to get a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function view(string $template = '', array $variables = []) : View;

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
    public function setTimezone() : void;

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
    public function setCompiled() : void;

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
    public function abort(int $code = 404, string $message = '', array $headers = [], string $view = null) : void;

    /**
     * Run the application.
     *
     * @return void
     */
    public function run() : void;

    /**
     * Register a service provider.
     *
     * @param string $serviceProvider The service provider
     *
     * @return void
     */
    public function register(string $serviceProvider) : void;
}
